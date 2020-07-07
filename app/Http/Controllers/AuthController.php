<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function response($status,$code,$msg) {
        $resp = [
            'status' => $status,
            'code' => $code,
            'value' => $msg
        ];
        return response()->json($resp);
    }

    public function login(Request $request) {
        $users =  new Users();
        $data_login = $users->is_exist($request->username,$request->password);
        switch($data_login) {
            case 'Username tidak ada':
                return $this->response('Failed', 404, 'Mohon koreksi kembali username anda');
                break;
            case 'Password tidak ada':
                return $this->response('Failed', 404, 'Mohon koreksi kembali password anda');
                break;
            case 'Sudah login':
                return $this->response('Failed', 500, 'Mohon tidak melakukan spam login!');
                break;
            default:
                $make_login = $users->make_login($data_login->id);
                if($make_login == 1) {
                    return $this->response('Success', 200, [
                        'message' => 'Simpan Key dibawah ini untuk melakukan request API',
                        'key' => $data_login->key,
                    ]);
                } else {
                    return $this->response('Failed', 500, 'Terdapat masalah pada proses login anda');
                }
                break;
        }
    }

    public function logout(Request $request) {
        $key = $request->header('Authorization');
        $users = new Users();
        $make_logout = $users->make_logout($key);
        if($make_logout == 1) {
            return $this->response('Success', 200, [
                'message' => 'Logout berhasil',
                'time' => Carbon::now(),
            ]);
        } else {
            return $this->response('Failed', 500, 'Terdapat masalah pada proses logout anda');
        }
    }
}
