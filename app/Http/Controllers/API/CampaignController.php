<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Comment;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index_campaign(Request $request)
    {
        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->status_verified_campaign)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Status Campaign Tidak Valid !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();
        $data = Campaign::where(function($query_where) use ($request) {
            if($request->status_verified_campaign != "All") {
                $query_where->where('status_verified_campaign', $request->status_verified_campaign);
            } else {
                $query_where->where('status_verified_campaign', 'Accepted');
            }
        })->get();

        for ($i=0; $i < count($data); $i++) { 
        }

        if (count($data) > 0) {
            $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Mendapatkan Data !', 'data' => $data];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Mendapatkan Data', 'data' => ''];
        }
        return response()->json($return);
    }

    public function store_campaign(Request $request)
    {

        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->title)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Title Harus Diisi !', 'data' => ''];
        }
        if (empty($request->description)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Description Harus Diisi !', 'data' => ''];
        }
        if (empty($request->picture) || !$request->hasFile('picture')) {
            return ['status' => 'error', 'code' => 413, 'message' => 'Picture Harus Diisi !', 'data' => ''];
        }
        if (empty($request->edit)) {
            return ['status' => 'error', 'code' => 413, 'message' => 'Status Edit Tidak Valid !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();
        if ($request->edit == 'false') {
            $data  = new Campaign();
            $data->status_verified_campaign       = "Pending";
            $data->tanggal_jam_add_campaign       = date('Y-m-d H:i:s');
            $data->level_user_creator_campaign_id = $user->level_users;
            $data->creator_campaign_id            = $user->id;
            $data->is_active                      = '1';
        } else {
            $data  = Campaign::find($request->id);
        }
        
        $data->title_campaign = $request->title;
        $data->description_campaign = $request->description;
        $data->save();

        // Save photo
        if ($request->hasFile('picture')) {
            if ($request->edit == 'true') {
                if ($data->picture != null) {
                  if (is_file('uploads/picture_campaign/' . $data->picture)) {
                    // Storage::delete($data->picture);
                    unlink('uploads/picture_campaign/' . $data->picture);
                  }
                }
            }

            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $filenameSimpan = $data->id_campaign . '-' . date('YmdHis') . '.' . $extension;
  
            // $path = $request->file('picture')->storeAs('bukti_absen_marketing', $filenameSimpan, 'public');
            $this->save_image_compress("picture", "picture_campaign/", $filenameSimpan, $request);
            $data->picture = $filenameSimpan;
        }

        $data->save();

        if ($data) {
            if ($request->edit == 'false') {
                $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Simpan Data !', 'data' => $data];
            } else {
                $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Ubah Data !', 'data' => $data];
            }
        } else {
            if ($request->edit == 'false') {
                $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Simpan Data', 'data' => ''];
            } else {
                $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Ubah Data', 'data' => ''];
            }
            
        }
        return response()->json($return);
    }

    public function detail_campaign(Request $request)
    {
        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->id_campaign)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Campaign Tidak Valid !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();
        $data = Campaign::find($request->id_campaign);
        if ($data) {
            $data->tanggal_buat_campaign = Carbon::parse($data->tanggal_jam_add_campaign)->isoFormat('D MMMM YYYY');

            $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Mendapatkan Data !', 'data' => $data];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Mendapatkan Data', 'data' => ''];
        }
        return response()->json($return);
    }

    public function destroy_campaign(Request $request)
    {
        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->id_campaign)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Campaign Tidak Valid !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();
        $data = Campaign::find($request->id_campaign);
        if ($data) {
            if ($data->picture != null) {
                if (is_file('uploads/picture_campaign/' . $data->picture)) {
                  // Storage::delete($data->picture);
                  unlink('uploads/picture_campaign/' . $data->picture);
                }
            }

            $data->delete();

            $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Hapus Data !', 'data' => ''];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Hapus Data', 'data' => ''];
        }
        return response()->json($return);
    }

    public function update_verified_status(Request $request)
    {
        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->id_campaign)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Campaign Tidak Valid !', 'data' => ''];
        }
        if (empty($request->status_verified_campaign)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Status Verifikasi Tidak Valid !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();
        $data = Campaign::find($request->id_campaign);
        if ($data && !$data->tanggal_jam_verifikasi_campaign) {
            $data->status_verified_campaign = $request->status_verified_campaign;
            $data->tanggal_jam_verifikasi_campaign = date('Y-m-d H:i:s');
            $data->verified_user_id = $user->id;

            $data->save();

            $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Mengubah Status Data !', 'data' => $data];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Mengubah Status Data', 'data' => ''];
        }
        return response()->json($return);
    }

    public function get_comment_campaign(Request $request)
    {
        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->id_campaign)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Campaign Tidak Valid !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();
        $data = Comment::join('users as us', 'us.id', 'comment.creator_comment_id')
                        ->where('campaign_id', $request->id_campaign)
                        ->get();

        if (count($data) > 0) {
            $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Mendapatkan Data !', 'data' => $data];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Mendapatkan Data', 'data' => ''];
        }
        return response()->json($return);
    }

    public function store_comment_campaign(Request $request)
    {
        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->id_campaign)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Campaign Tidak Valid !', 'data' => ''];
        }
        if (empty($request->text_comment)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Comment Harus Diisi !', 'data' => ''];
        }
        if (empty($request->edit_comment)) {
            return ['status' => 'error', 'code' => 413, 'message' => 'Status Edit Tidak Valid !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();
        if ($request->edit_comment == 'false') {
            $data  = new Comment();
            $data->tanggal_jam_comment  = date('Y-m-d H:i:s');
            $data->creator_comment_id   = $user->id;
            $data->campaign_id          = $request->id_campaign;
        } else {
            $data  = Comment::find($request->id_comment);
        }
        
        $data->text_comment = $request->text_comment;
        $data->save();

        if ($data) {
            if ($request->edit_comment == 'false') {
                $return = ['status' => 'success', 'code' => 200, 'message' => 'Komentar Berhasil Dikirim !', 'data' => $data];
            } else {
                $return = ['status' => 'success', 'code' => 200, 'message' => 'Komentar Berhasil Diubah !', 'data' => $data];
            }
        } else {
            if ($request->edit_comment == 'false') {
                $return = ['status' => 'error', 'code' => 500, 'message' => 'Komentar Gagal Dikirim', 'data' => ''];
            } else {
                $return = ['status' => 'error', 'code' => 500, 'message' => 'Komentar Gagal Diubah', 'data' => ''];
            }
            
        }
        return response()->json($return);
    }
}
