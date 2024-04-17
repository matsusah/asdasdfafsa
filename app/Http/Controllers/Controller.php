<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Intervention\Image\ImageManager;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function save_image_compress($jenis_simpan_foto, $nama_folder, $nama_file, $request) {
        if (empty($nama_folder)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Paremeter Nama Folder Harus Diiisi !'];
        }
        if (empty($request)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Parameter Request Harus Diiisi !'];
        }
        if (empty($nama_file)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Parameter Nama File Harus Diisi !'];
        }

        // Simpan Gambar Start
        $image = $request->file($jenis_simpan_foto);
        $image_manager = new ImageManager();
        $imgFile = $image_manager->make($image->getRealPath());
        $imgFile->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save("uploads/".$nama_folder. $nama_file);
        // Simpan Gambar End

        return ['status' => 'success', 'code' => 200, 'message' => 'Save Image Compress Berhasil !'];
    }

    function set_status_otomatic() {
        $data_campaign_all = Campaign::where('status_verified_campaign', 'Pending')->get();

        // Filter data campaign yang tanggal jam pembuatan campaign selisih 30 hari dari sekarang
        $filteredDataCampaign = $data_campaign_all->filter(function ($dca) {
            $dateTime = $dca->tanggal_jam_add_campaign;
            $dateTimeDiff = now()->diffInDays(\Carbon\Carbon::parse($dateTime));

            return $dateTimeDiff > 30;
        });

        for ($i=0; $i < count($filteredDataCampaign); $i++) { 
            $filteredDataCampaign[$i]->status_verified_campaign = "Expired";
            $filteredDataCampaign[$i]->tanggal_jam_verifikasi_campaign = date('Y-m-d H:i:s');
            $filteredDataCampaign[$i]->save();
        }

        if (count($filteredDataCampaign) > 0) {
            $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Ubah Data !'];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Ubah Data'];
        }
        return response()->json($return);
    }
}
