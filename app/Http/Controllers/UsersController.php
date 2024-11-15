<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\GroupAdvertisement;
use App\Models\Advertisement;
use App\Models\Logins;
use App\Models\Horario;
use App\Models\Day;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class GroupUsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $groups_general = Group::get();
        $company = Company::findOrFail(1);;
        $users = User::get();

        return view('users', compact('users','company','groups_general'));
    }

    public function validateForm()
    {
        $messages = [
            'name.required'         => 'Debe ingresar un nombre.',
            'user.required'         => 'Debe ingresar un Usuario.',
            'password.required'     => 'Debe ingresar una contraseña.',
            'telefono.required'     => 'Debe ingresar un teléfono.',
            'genero.required'       => 'Debe seleccionar el género.',
            'fecha_naci.required'   => 'Debe ingresar la fecha de nacimiento.',
            'obs.required'          => 'Debe ingresar una observación.'
        ];

        $rules = [
            'name'                  => 'required',
            'user'                  => 'required',
            'password'              => 'required',
            'telefono'              => 'required',
            'genero'                => 'required',
            'fecha_naci'            => 'required',
            'obs'                   => 'required'
        ];

        request()->validate($rules, $messages);
        return request()->all();
    }

    public function validateModalGroup()
    {
        $messages = [
            'group_id.required'         => 'Debe seleccionar un Grupo.',
        ];

        $rules = [
            'group_id'                  => 'required',
        ];

        request()->validate($rules, $messages);
        return request()->all();
    }

    public function AddGroup()
    {
        $this->validateModalGroup();

        $user_id = request('user_id');
        $group_id = request('group_id');

        $info = [
            'type'  => 1,
            'title' => 'Bien',
            'msg'   => 'Grupo agregado con éxito.',
        ];

        $element = new UserGroup();
        $element->user_id = $user_id;
        $element->group_id = $group_id;
        $element->save();

        $list = $this->listGroup();

        return response()->json([
            'info'  => $info,
            'list'  => $list
        ]);
    }

    public function SaveUser()
    {
        $this->validateForm();

        $company = Company::findOrFail(1);;

        $id = request('id');
        $name = request('name');
        $user = request('user');
        $password = bcrypt(request('password'));
        $telefono = request('telefono');
        $genero = request('genero');
        $fecha_naci = request('fecha_naci');
        $obs = request('obs');

        if (isset($id)) {
            $element = User::findOrFail($id);
        } else {
            $element = new User();
        }


        $element->name = $name;
        $element->user = $user . $company->sufijo;
        $element->password = $password;
        $element->remember_token = $password;
        $element->telefono = $telefono;
        $element->genero = $genero;
        $element->fecha_naci = $fecha_naci;
        $element->obs = $obs;
        $element->save();

        $groups_general = Group::get();
        $company = Company::findOrFail(1);;
        $users = User::get();

        return redirect()->back()->with([
          'groups_general' => $groups_general,
          'company' => $company,
          'users' => $users
      ]);
    }

    public function DeleteUser()
    {

        $id = request('id');
        $element = User::findOrFail($id);
        $element->delete();

        $type = 3;
        $title = 'Bien';
        $msg = 'Usuario eliminado exitosamente.';


        return response()->json([
            'type'  => $type,
            'title' => $title,
            'msg'   => $msg
        ]);
    }
}