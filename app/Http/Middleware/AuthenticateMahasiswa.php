<?php

namespace App\Http\Middleware;

use Closure;
use App\Mahasiswa_Login;
use Illuminate\Contracts\Auth\Factory as Auth;

class AuthenticateMahasiswa {

    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        if ($this->auth->guard($guard)->guest()) {
            if ($request->has('api_token_mhs')) {
                $token = $request->input('api_token_mhs');
                $check_token = Mahasiswa_Login::where('api_token_mhs', $token)->first();
                if ($check_token == null) {
                    $res['success'] = false;
                    $res['message'] = 'Permission not allowed!';
                    return response($res, 401);
                }
            } else {
                $res['success'] = false;
                $res['message'] = 'Login Mahasiswa please!';
                return response($res, 401);
            }
        }
        return $next($request);
    }

}
