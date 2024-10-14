<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Logins;
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
            $logins = new Logins();
            $logins->user_id = Auth::user()->id;
            $logins->login = 1;
            $logins->created_at_user = Auth::user()->name;
            $logins->save();

            $type = 3;
            $title = 'Ok!';
            $msg = 'Bienvenido, ' . Auth::user()->name;
            $url = route('dashboard.home.index');
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
        $logouts = new Logins();
        $logouts->user_id = Auth::user()->id;
        $logouts->login = 0;
        $logouts->created_at_user = Auth::user()->name;
        $logouts->save();

        Auth::logout();
        return redirect()->route('dashboard.index');
    }
}
