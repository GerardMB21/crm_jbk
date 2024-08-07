<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    
    public function index()
    {
        $users = User::select(
            'id',
            'name',
            'user',
            DB::Raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s") as creacion'),
            DB::Raw('DATE_FORMAT(updated_at, "%Y-%m-%d %H:%i:%s ") as actualizacion')
        )->get();

        return view('user')->with(compact('users'));
    }

    public function validateForm()
    {
        $messages = [
            'name.required'         => 'Debe ingresar un nombre.',
            'user.required'         => 'Debe ingresar un Usuario.',
            'password.required'     => 'Debe ingresar una contraseña.'
        ];

        $rules = [
            'name'                  => 'required',
            'user'                  => 'required',
            'password'              => 'required'
        ];

        request()->validate($rules, $messages);
        return request()->all();
    }

    public function store()
    {
        $this->validateForm();

        $id = request('id');
        $name = request('name');
        $user = request('user');
        $password = bcrypt(request('password'));

        if (isset($id)) {
            $element = User::findOrFail($id);
            $msg = 'Usuario actualizado exitosamente.';
        } else {
            $element = new User();
            $msg = 'Usuario creado exitosamente.';
        }


        $element->name = $name;
        $element->user = $user;
        $element->password = $password;
        $element->remember_token = $password;
        $element->save();

        $type = 3;
        $title = 'Bien';
        $url = route('dashboard.user.index');


        return response()->json([
            'type'  => $type,
            'title' => $title,
            'msg'   => $msg,
            'url'   => $url
        ]);
    }

    public function delete()
    {

        $id = request('id');
        $element = User::findOrFail($id);
        $element->delete();

        $type = 3;
        $title = 'Bien';
        $msg = 'Usuario eliminado exitosamente.';
        $url = route('dashboard.user.index');


        return response()->json([
            'type'  => $type,
            'title' => $title,
            'msg'   => $msg,
            'url'   => $url
        ]);
    }
}
