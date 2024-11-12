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
        $companies = Company::get();
        $groups = Group::leftjoin('horarios', 'horarios.id', '=', 'groups.horario_id')
                        ->select(
                            'groups.id as id',
                            'groups.company_id as company_id',
                            'groups.campana_id as campana_id',
                            'groups.name as name',
                            'groups.ip as ip',
                            'groups.horario_id as horario_id',
                            'groups.state as state',
                            'groups.perfil_id as perfil_id',
                            'groups.created_at as created_at',
                            'groups.updated_at as updated_at',
                            'groups.created_at_user as created_at_user',
                            'groups.updated_at_user as updated_at_user',
                            'groups.deleted_at as deleted_at',
                            'groups.permissions as permissions',
                            'horarios.name as horario_name',
                        )
                        ->get();
        $hours = Horario::get();

        return view('groups', compact('companies','groups','hours'));
    }

    public function validateForm()
    {
        $messages = [
            'company_id.required'      => 'Debe seleccionar una Compañía.',
            'name.required'            => 'Debe ingresar un Nombre.',
            'ip.required'              => 'Debe ingresar una IP.'
        ];

        $rules = [
            'company_id'            => 'required',
            'name'                  => 'required',
            'ip'                    => 'required'
        ];

        request()->validate($rules, $messages);
        return request()->all();
    }

    public function SaveGroup(Request $request)
    {
        $this->validateForm();

        $id = request('id');
        $company_id = request('company_id');
        $name = request('name');
        $ip = request('ip');
        $permissions = request('permissions');
        $horario_id = request('horario_id');

        if (isset($id)) {
            $element = Group::findOrFail($id);
            $element->updated_at_user = Auth::user()->name;
            $msg = 'Grupo actualizado exitosamente.';
        } else {
            $element = new Group();
            $element->created_at_user = Auth::user()->name;
            $msg = 'Grupo creado exitosamente.';
        }

        $element->company_id = $company_id;
        $element->name = $name;
        $element->ip = $ip;
        $element->permissions = $permissions;
        $element->horario_id = $horario_id;
        $element->save();

        $companies = Company::get();
        $groups = Group::leftjoin('horarios', 'horarios.id', '=', 'groups.horario_id')
                        ->select(
                            'groups.id as id',
                            'groups.company_id as company_id',
                            'groups.campana_id as campana_id',
                            'groups.name as name',
                            'groups.ip as ip',
                            'groups.horario_id as horario_id',
                            'groups.state as state',
                            'groups.perfil_id as perfil_id',
                            'groups.created_at as created_at',
                            'groups.updated_at as updated_at',
                            'groups.created_at_user as created_at_user',
                            'groups.updated_at_user as updated_at_user',
                            'groups.deleted_at as deleted_at',
                            'groups.permissions as permissions',
                            'horarios.name as horario_name',
                        )
                        ->get();
        $hours = Horario::get();

        return redirect()->back()->with([
            'companies' => $companies,
            'groups' => $groups,
            'hours' => $hours
        ]);
    }

    public function delete()
    {

        $id = request('id');

        $days = Day::where('horario_id', $id);
        $days->delete();

        $horario = Horario::findOrFail($id);
        $horario->delete();

        $type = 3;
        $title = 'Bien';
        $msg = 'Horario eliminado exitosamente.';
        $url = route('dashboard.horario.index');


        return response()->json([
            'type'  => $type,
            'title' => $title,
            'msg'   => $msg,
            'url'   => $url
        ]);
    }
}