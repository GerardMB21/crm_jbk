<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function index()
    {

        $groups = Group::get();
        $company = Company::findOrFail(1);;
        $users = User::select(
            'id',
            'name',
            'user',
            DB::Raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s") as creacion'),
            DB::Raw('DATE_FORMAT(updated_at, "%Y-%m-%d %H:%i:%s ") as actualizacion')
        )->get();

        return view('user')->with(compact('users', 'company', 'groups'));
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

        $company = Company::findOrFail(1);;

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
        $element->user = $user . $company->sufijo;
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

    public function listGroup()
    {

        $user_id = request('user_id');

        $groups = UserGroup::select(
            'user_groups.id  as id',
            'groups.name as group_name'
        )
            ->leftjoin('groups', 'groups.id', '=', 'user_groups.group_id')
            ->where('user_groups.user_id', $user_id)
            ->get();

        return $groups;
    }

    public function deleteGroup()
    {
        $id = request('id');
        $group = UserGroup::findOrFail($id);
        $group->delete();

        return $this->listGroup();
    }
}
