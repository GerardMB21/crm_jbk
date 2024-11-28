<?php

namespace App\Http\Controllers;

use App\Models\Campain;
use App\Models\Company;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Group;
use App\Models\Block;
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
use Illuminate\Support\Facades\Storage;

class BlockCampsController extends Controller
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
        $id = 0;
        $campaigns = Campain::get();
        $blocks = [];

        return view('blocks', compact('id','campaigns','blocks','modules'));
    }

    public function indexWithId($id)
    {
        $modules = $this->modules();
        $campaigns = Campain::get();
        $blocks = Block::leftjoin('campains', 'campains.id', '=', 'blocks.campain_id')
                        ->select(
                            'blocks.id as id',
                            'campains.id as campaign_id',
                            'campains.name as campaign_name',
                            'blocks.name as name',
                            'blocks.order as order',
                            'blocks.state as state',
                        )
                        ->where('campain_id', $id)
                        ->orderBy('blocks.order','asc')
                        ->get();

        return view('blocks', compact('id','campaigns','blocks','modules'));
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

        $modules = Module::whereIn('id', [2])
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

    public function validateModalForm()
    {

        $messages = [
            'name.required'         => 'Debe completar el nombre.',
            'order.required'         => 'Debe completar el Orden.',
            'campain_id.required'         => 'Debe elegir una Campaña.',
        ];

        $rules = [
            'name'                  => 'required',
            'order'                  => 'required',
            'campain_id'                  => 'required',
        ];

        request()->validate($rules, $messages);
        return request()->all();
    }

    public function SaveBlock()
    {

        $this->validateModalForm();

        $id = request('id');
        $campain_id = request('campaign_id');
        $name = request('name');
        $order = request('order');
        $state = 1; //activo

        if (isset($id)) {
            $tab_state =  Block::findOrFail($id);
            $msg = 'Registro actualizado exitosamente';
            $tab_state->updated_at_user = Auth::user()->name;
        } else {
            $tab_state = new Block();
            $tab_state->campain_id = $campain_id;
            $tab_state->state = $state;
            $tab_state->created_at_user = Auth::user()->name;
            $msg = 'Registro creado exitosamente';
        }

        $tab_state->name = $name;
        $tab_state->order = $order;

        $tab_state->save();

        $campaigns = Campain::get();
        $blocks = Block::leftjoin('campains', 'campains.id', '=', 'blocks.campain_id')
                        ->select(
                            'blocks.id as id',
                            'campains.id as campaign_id',
                            'campains.name as campaign_name',
                            'blocks.name as name',
                            'blocks.order as order',
                            'blocks.state as state',
                        )
                        ->where('campain_id', $campain_id)
                        ->orderBy('blocks.order','asc')
                        ->get();

        return redirect()->back()->with([
            'id' => $campain_id,
            'campaigns' => $campaigns,
            'blocks' => $blocks,
        ]);
    }

    public function getBlock()
    {
        $id = request('id');
        $elements = Block::findOrFail($id);
        return $elements;
    }

    public function DeleteBlock($id)
    {
        $element = Block::findOrFail($id);
        $element->delete();

        $msg = 'Registro eliminado exitosamente';
        $type = 1;
        $title = '¡Ok!';

        return response()->json([
            'type'    => $type,
            'title'    => $title,
            'msg'    => $msg,
        ]);
    }

    public function deshabilitar()
    {
        $element = Block::findOrFail(request('id'));
        $element->state = 0;
        $element->save();

        $msg = 'Registro deshabilitado exitosamente';
        $type = 1;
        $title = '¡Ok!';

        return response()->json([
            'type'    => $type,
            'title'    => $title,
            'msg'    => $msg,
        ]);
    }

    public function habilitar()
    {
        $element = Block::findOrFail(request('id'));
        $element->state = 1;
        $element->save();

        $msg = 'Registro habilitado exitosamente';
        $type = 1;
        $title = '¡Ok!';

        return response()->json([
            'type'    => $type,
            'title'    => $title,
            'msg'    => $msg,
        ]);
    }
}