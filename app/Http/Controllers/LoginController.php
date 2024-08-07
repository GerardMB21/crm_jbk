<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        if (Auth::attempt([
            'user'        => request('user'),
            'password'    => request('password')
        ], true)) {
            $type = 3;
            $title = 'Ok!';
            $msg = 'Bienvenido, ' . Auth::user()->name;
            $url = route('dashboard.user.index');
        } else {
            $type = 2;
            $title = "Error!";
            $msg = "Verifique los datos ingresados";
            $url = route('dashboard.index');
        }



        return response()->json([
            'type'  => $type,
            'title' => $title,
            'msg'   => $msg,
            'url'   => $url
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('dashboard.index');
    }
}
