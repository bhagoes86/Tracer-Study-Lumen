<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller {

    /**
     * Index login controller
     *
     * When user success login will retrive callback as api_token
     */
    public function index(Request $request) {
        $hasher = app()->make('hash');
        $email = $request->input('email');
        $password = $request->input('password');
        $login = User::where('email', $email)->first();
        if (!$login) {
            $res['success'] = false;
            $res['message'] = 'Your email or password incorrect!';
            return response($res, 401);
        } else {
            if ($hasher->check($password, $login->password)) {
                if ($login->api_token == NULL) {
                    $api_token = sha1($email . time() . $password);
                    $create_token = User::where('id', $login->id)->update(['api_token' => $api_token]);
                    if ($create_token) {
                        $res['success'] = true;
                        $res['api_token'] = $api_token;
                        $res['message'] = $login;
                        return response($res);
                    }
                } else {
                    $res['success'] = true;
                    $res['api_token'] = $login->api_token;
                    $res['message'] = $login;
                    return response($res);
                }
            } else {
                $res['success'] = false;
                $res['message'] = 'You email or password incorrect!';
                return response($res, 401);
            }
        }
    }

}
