<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function login(Request $request)
    {

        // Validate Data Start
        if (empty($request->email)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Email Harus Diisi!'];
        }
        if (empty($request->password)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Password Harus Diisi!'];
        }
        $cekEmail = Users::where('email', $request->email)->where('deleted_at', null)->first();
        if (!$cekEmail) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Akun Tidak Dapat Ditemukan, Silahkan Cek Kembali Email Anda !!'];
        }
        // Validate Data End

        $data['email'] = strip_tags($request->email);
        $data['password'] = strip_tags($request->password);
        $data['deleted_at'] = null;
        $cek = Auth::attempt($data);
        if ($cek) {
            $user = Users::find(Auth::id());
            if ($user->is_active == '1') {
                if (empty($user->token)) {
                    $user->token = Str::random(15) . substr(md5(date('Y-m-d H:i:s', strtotime('now'))), -15);
                    $user->save();
                }

                // Jalanakan Set Verifikasi Otomatis Campaign
                $this->set_status_otomatic();

                $return = ['status' => 'success', 'code' => 200, 'message' => 'Login Berhasil !', 'data' => $user];
            } else {
                $return = ['status' => 'error', 'code' => 401, 'message' => 'Akun Belum Aktif, Silahkan menghubungin Admin untuk Aktifkan Akun !!.', 'data' => ''];
            }
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Email / Password Salah', 'data' => ''];
        }
        return response()->json($return);
    }

    public function register(Request $request)
    {

        // Validate Data Start
        if (empty($request->edit)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Status Edit Tidak Valid!'];
        }
        if (empty($request->username)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Username Harus Diisi!'];
        }
        if (empty($request->email)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Email Harus Diisi!'];
        }
        if (!empty($request->email)) {
            $cekEmail = Users::where('email', $request->email)->where('deleted_at', null)->first();
            if ($cekEmail && $cekEmail->id != $request->id) {
                return ['status' => 'error', 'code' => 500, 'message' => 'Email Sudah Digunakan Orang Lain, Silahkan Menggunakan Email Lain!'];
            }
        }
        if (empty($request->password) && $request->edit == 'false') {
            return ['status' => 'error', 'code' => 500, 'message' => 'Password Harus Diisi!'];
        }
        // Validate Data End

        if ($request->edit == 'false') {
            $newuser            = new Users;
            $newuser->token     = Str::random(15) . substr(md5(date('Y-m-d H:i:s', strtotime('now'))), -15);
            $newuser->password  = Hash::make($request->password);
        } else {
            $newuser            = Users::find($request->id);
            if (!empty($request->password)) {
                $newuser->password  = Hash::make($request->password);
            }
        }
        $newuser->name = $request->username;
        $newuser->email = $request->email;
        $newuser->level_users = 'Pengguna';
        $newuser->is_active = '1';
        $newuser->save();

        if ($newuser) {
            if ($request->edit == 'false') {
                return ['code' => 200, 'status' => 'success', 'message' => 'Berhasil Registrasi!', 'data' => $newuser];
            } else {
                return ['code' => 200, 'status' => 'success', 'message' => 'Berhasil Diubah!', 'data' => $newuser];
            }
        } else {
            if ($request->edit == 'false') {
                return ['code' => 200, 'status' => 'success', 'message' => 'Gagal Registrasi!'];
            } else {
                return ['code' => 200, 'status' => 'success', 'message' => 'Gagal Diubah!'];
            }
        }
    }
}
