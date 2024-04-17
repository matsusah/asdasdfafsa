<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index_faq_pagination(Request $request)
    {
        $user = Users::where('token', $request->token)->first();
        $search = $request->search;
        if ($search == 'null') {
            $cari = '';
        } else {
            $cari = $search;
        }
        // Total
        $total = Faq::where('title_faq', 'like', "%$cari%")
            // ->offset($request->page*10)
            // ->limit(10)
            ->get();

        // Pagination
        $data = Faq::where('title_faq', 'like', "%$cari%")
            ->offset($request->page * 10)
            ->limit(10)
            ->get();

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->tanggal_buat_faq = Carbon::parse($data[$i]->tanggal_jam_add_faq)->isoFormat('D MMMM YYYY');
        }

        $pagination = [
            'previous_page' => $request->page - 1,
            'current_page' => (int)$request->page,
            'next_page' => $request->page + 1,
            'next_page' => $request->page + 1,
            'total' => ceil(count($total) / 10)
        ];
        if (count($data) > 0) {
            $return = ['status' => 'success', 'code' => 200, 'message' => 'Data Ditemukan !!', 'data' => $data, 'pagination' => $pagination];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Data Tidak Ditemukan !!', 'data' => []];
        }
        return response()->json($return);
    }

    public function store_faq(Request $request)
    {
        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->title)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Title Harus Diisi !', 'data' => ''];
        }
        if (empty($request->description)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Describe Harus Diisi !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();
        if (in_array($user->level_users, ['Admin'])) {
            $waktu_sekarang = Carbon::now('Asia/Jakarta');

            // Check data absen marketing start
            //   $check_data = AbsenMarketing::where('customer_id', $request->customer_id)
            //                               ->where('tanggal_bukti_absen_marketing', date('Y-m-d'))
            //                               ->first();
            //   if($check_data) {
            //     $return = ['status' => 'error', 'code' => 500, 'message' => 'Sudah Melakukan Kunjungan Hari Ini !', 'data' => ''];
            //     return response()->json($return);
            //   }
            // Check data absen marketing end  

            $data = new Faq();
            $data->title_faq = $request->title;
            $data->description_faq = $request->description;
            $data->is_active = '1';
            $data->tanggal_jam_add_faq = date('Y-m-d H:i:s');
            $data->creator_faq_id = $user->id;
            $data->save();

            if ($data) {
                $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Simpan Data !', 'data' => $data];
            } else {
                $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Simpan Data', 'data' => ''];
            }
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Tidak Punya Hak Akses !', 'data' => ''];
        }
        return response()->json($return);
    }

    public function detail_faq(Request $request)
    {
        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->id_faq)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Faq Tidak Valid !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();
        $data = Faq::find($request->id_faq);
        if ($data) {
            $data->tanggal_buat_faq = Carbon::parse($data->tanggal_jam_add_faq)->isoFormat('D MMMM YYYY');

            $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Mendapatkan Data !', 'data' => $data];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Mendapatkan Data', 'data' => ''];
        }
        return response()->json($return);
    }
}
