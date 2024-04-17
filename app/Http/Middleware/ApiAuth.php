<?php

namespace App\Http\Middleware;

use App\Models\Users;
use Closure;
use Illuminate\Http\Request;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->token){
            $cek = Users::where('token',$request->token)->first(); //get data user berdasarkan token
            /* cek data user*/
            if ($cek) {
              if ($cek->is_active == 1) {
                return $next($request);
              } else {
                $return = ['status'=>'Forbidden', 'code'=>403, 'message' => 'Akun sedang di Non-Aktifkan, Silahkan menghubungin Admin untuk Aktifkan Akun !!.'];
                return response()->json($return);
              }
            } else {
                $return = ['status'=>'unauthorized', 'code'=>401, 'message' => 'Permintaan anda tidak bisa diproses.'];
                return response()->json($return);
            }
        }else {
            $return = ['status'=>'unauthorized', 'code'=>401, 'message' => 'Permintaan anda tidak bisa diproses.'];
            return response()->json($return);
        }
        return $next($request);
    }
}
