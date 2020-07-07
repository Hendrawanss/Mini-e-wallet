<?php

namespace App\Http\Middleware;

use Closure;
use App\Users;
use App\Http\Middleware\AuthMiddleware;

class AdminMiddleware
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
        $resp = new AuthMiddleware();
        if($users->is_admin($key) == 'admin'){
            return $next($request);
        } else {
            return $resp->response('Failed', 500, 'Fitur ini kami tujukan untuk admin saja!');
        }
    }
}
