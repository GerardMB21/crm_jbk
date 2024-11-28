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
use App\Models\Campain;
use App\Models\ModuleInGroup;
use App\Models\Module;
use App\Models\SectionInGroup;
use App\Models\Section;
use App\Models\SubSectionInGroup;
use App\Models\SubSection;

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
        $modules = $this->modules();
        $campaigns = Campain::get();
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

        return view('groups', compact('companies','groups','hours','campaigns','modules'));
    }

    public function modules()
    {
        $userId = Auth::user()->id;
        $userGroup = UserGroup::where('user_id', $userId)
                                ->first();
        $group = Group::where('id', $userGroup->group_id)
                        ->first();
        $modulesGroup = ModuleInGroup::where('group_id', $group->id)
                                    ->get();
        $sectionsGroup = SectionInGroup::where('group_id', $group->id)
                                        ->get();
        $subSectionsGroup = SubSectionInGroup::where('group_id', $group->id)
                                            ->get();

        $modulesIds = [];
        foreach ($modulesGroup as $moduleGroup) {
            $modulesIds[] = $moduleGroup->module_id;
        };

        $sectionsIds = [];
        foreach ($sectionsGroup as $sectionGroup) {
            $sectionsIds[] = $sectionGroup->section_id;
        };

        $subSectionsIds = [];
        foreach ($subSectionsGroup as $subSectionGroup) {
            $subSectionsIds[] = $subSectionGroup->sub_section_id;
        };

        $modules = Module::whereIn('id', [1])
                        ->get();
        $sections = Section::whereIn('id', $sectionsIds)
                        ->orderBy('order','asc')
                        ->get();
        $subSections = SubSection::whereIn('id', $subSectionsIds)
                                ->get();

        $result = $modules->map(function ($module) use ($sections, $subSections) {

            $moduleSections = $sections->where('module_id', $module->id)->map(function ($section) use ($subSections)
            {
                $sectionSubSections = $subSections->where('section_id', $section->id);

                $section->subSections = $sectionSubSections->values();

                return $section;
            });

            $module->sections = $moduleSections->values();

            return $module;
        });

        $modules = $result;

        return $modules;
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

        $campaigns = Campain::get();
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

        $modules = $this->modules();

        return redirect()->back()->with([
            'companies' => $companies,
            'groups' => $groups,
            'hours' => $hours,
            'campaigns' => $campaigns,
            'modules' => $modules,
        ]);
    }

    public function DeleteGroup()
    {

        $id = request('id');
        $element = Group::findOrFail($id);
        $element->delete();

        $type = 3;
        $title = 'Bien';
        $msg = 'Grupo eliminado exitosamente.';


        return response()->json([
            'type'  => $type,
            'title' => $title,
            'msg'   => $msg,
        ]);
    }
}