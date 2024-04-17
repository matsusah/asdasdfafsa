<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index_dashboard(Request $request)
    {
        $check_data_campaign_admin = [];
        $data_campaign = [];
        $data_campaign_map = [];
        $request_accepted_count = 0;
        $request_declined_count = 0;
        $request_submited_count = 0;

        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }

        $user = Users::where('token', $request->token)->first();

        // Check data for table start
        $check_data = Campaign::select('creator_campaign_id')->get();
        if($user->level_users == "Admin") {
            for ($k=0; $k < count($check_data); $k++) { 
                if(!in_array($check_data[$k]->creator_campaign_id, $check_data_campaign_admin)) {
                    array_push($check_data_campaign_admin, $check_data[$k]->creator_campaign_id);
                }
            }

            $data_campaign = Users::whereIn('id', $check_data_campaign_admin)->get();
        } else if($user->level_users == "Pengguna") {
            $data_campaign = Campaign::where('creator_campaign_id', $user->id)->get();
        }

        // Check data for table end

        // Get count request_accepted, request_declined, request_submited
        $request_accepted_count = Campaign::where('status_verified_campaign', 'Accepted')->get()->count();
        $request_declined_count = Campaign::where('status_verified_campaign', 'Declined')->get()->count();
        if($user->level_users == "Pengguna") {
            $request_accepted_count = Campaign::where('status_verified_campaign', 'Accepted')
                                        ->where('creator_campaign_id', $user->id)
                                        ->get()
                                        ->count();

            $request_submited_count = Campaign::where('status_verified_campaign', 'Pending')
                                              ->where('creator_campaign_id', $user->id)
                                              ->get()
                                              ->count();
        }

        // Map campaign data start
        $index = 0;
        for ($i=0; $i < count($data_campaign); $i++) { 
            $index++;
            if($user->level_users == "Pengguna") {
                $data_map_temp = [
                    'number' => $index,
                    'second_column' => $data_campaign[$i]->title_campaign,
                    'status_campaign' => $data_campaign[$i]->status_verified_campaign,
                    'id_users' => $data_campaign[$i]->creator_campaign_id,
                ];
            } else if($user->level_users == "Admin") {
                $data_map_temp = [
                    'number' => $index,
                    'second_column' => $data_campaign[$i]->name,
                    'status_campaign' => '',
                    'id_users' => $data_campaign[$i]->id,
                ];
            }

            array_push($data_campaign_map, $data_map_temp);
        }
        // Map campaign data end


        $data_map = [
            'request_accepted_count' => $request_accepted_count,
            'request_declined_count' => $request_declined_count,
            'request_submited_count' => $request_submited_count,
            'data_table' => $data_campaign_map,
            'level_users' => $user->level_users,
        ];

        if (count($data_campaign) > 0) {
            $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Mendapatkan Data !', 'data' => $data_map];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Mendapatkan Data', 'data' => ''];
        }
        return response()->json($return);
    }

    public function detail_review(Request $request)
    {
        if (empty($request->token)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Token Tidak Valid !', 'data' => ''];
        }
        if (empty($request->id_users)) {
            return ['status' => 'error', 'code' => 500, 'message' => 'Data Users Tidak Valid !', 'data' => ''];
        }

        $user_view = Users::where('token', $request->token)->first();
        $data = Campaign::where('creator_campaign_id', $request->id_users)->get();
        if (count($data) > 0) {
            for ($i=0; $i < count($data); $i++) { 
                $data[$i]->tanggal_buat_campaign = Carbon::parse($data[$i]->tanggal_jam_add_campaign)->isoFormat('D MMMM YYYY');
            }

            $return = ['status' => 'success', 'code' => 200, 'message' => 'Barhasil Mendapatkan Data !', 'data' => $data];
        } else {
            $return = ['status' => 'error', 'code' => 500, 'message' => 'Gagal Mendapatkan Data', 'data' => ''];
        }
        return response()->json($return);
    }
}
