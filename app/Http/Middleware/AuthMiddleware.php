<?php

namespace App\Http\Middleware;

use Closure;
use App\Users;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = $request->header('Authorization');
        $users = new Users();
        if($users->is_login($key) == 1){
            return $next($request);
        } else {
            return $this->response('Failed', 500, 'Kami memerlukan authentikasi untuk melanjutkan request!');
        }
    }

    public function response($status,$code,$msg) {
        $resp = [
            'status' => $status,
            'code' => $code,
            'value' => $msg
        ];
        return response()->json($resp);
    }
}
