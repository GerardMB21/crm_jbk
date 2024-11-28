<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\GroupAdvertisement;
use App\Models\Advertisement;
use App\Models\Logins;
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

class ProfileController extends Controller
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

        $modules = Module::whereIn('id', $modulesIds)
                        ->get();
        $sections = Section::whereIn('id', $sectionsIds)
                            ->orderBy('order','asc')
                            ->get();
        $subSections = SubSection::whereIn('id', $subSectionsIds)
                                ->get();

        $result = $modules->map(function ($module) use ($sections, $subSections) {

            $moduleSections = $sections->where('module_id', $module->id)->sortBy('order')->map(function ($section) use ($subSections)
            {
                $sectionSubSections = $subSections->where('section_id', $section->id)->sortBy('order');

                $section->subSections = $sectionSubSections->values();

                return $section;
            });

            $module->sections = $moduleSections->values();

            return $module;
        });

        $modules = $result;

        $campaigns = Campain::get();
        $userId = Auth::user()->id;
        $user = User::where('id', $userId)->first();
        $logins = Logins::where('user_id', $userId)
                        ->orderBy('created_at', 'asc')
                        ->get();
        $userGroup = UserGroup::where('user_id', $userId)->first();
        $group = Group::where('id', $userGroup->group_id)->first();
        $groupAdvertisement = GroupAdvertisement::where('group_id', $group->id)
                                                ->orderBy('created_at', 'asc')
                                                ->get();

        $events = [];
        $advertisementId = [];

        for ($i=0; $i < count($logins); $i++) { 
            $log = $logins[$i];
            $date_obj = $log->created_at;

            if ($log->login) {
                array_push($events, [
                    'title' => $date_obj->format('g:i A'),
                    'start' => $date_obj->format('Y-m-d'),
                    'color' => '#34C38F',
                    'icon' => 'uil-arrow-to-right'
                ]);
            } else {
                array_push($events, [
                    'title' => $date_obj->format('g:i A'),
                    'start' => $date_obj->format('Y-m-d'),
                    'color' => '#F46A6A',
                    'icon' => 'uil-left-arrow-to-left'
                ]);
            };
        };
        foreach ($groupAdvertisement as $GA) {
            array_push($advertisementId, $GA->advertisement_id);
        };

        $advertisements = Advertisement::whereIn('advertisements.id', $advertisementId)
                                        ->select(
                                            'files.name  as file_name',
                                            'advertisements.id  as id',
                                            'advertisements.state  as state',
                                            'advertisements.text  as text',
                                            'advertisements.title  as title',
                                            'advertisements.updated_at  as updated_at',
                                            'advertisements.updated_at_user  as updated_at_user',
                                            'advertisements.created_at  as created_at',
                                            'advertisements.created_at_user  as created_at_user',
                                            'advertisements.upload_id  as upload_id',
                                        )
                                        ->leftjoin('files', 'files.id', '=', 'advertisements.upload_id')
                                        ->orderBy('created_at', 'desc')
                                        ->get();

        return view('profile', compact('user','events','advertisements','group','campaigns','modules'));
    }
}