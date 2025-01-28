<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\GroupAdvertisement;
use App\Models\Advertisement;
use App\Models\Logins;
use App\Models\File;
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

class EnterpriseController extends Controller
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
        $company = Company::findOrFail(1);
        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);

        return view('enterprise', compact('company','campaigns','modules','user'));
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

        $modules = Module::whereIn('id', $modulesIds)
                        ->get();
        $sections = Section::whereIn('id', $sectionsIds)
                        ->orderBy('order','asc')
                        ->get();
        $subSections = SubSection::whereIn('id', $subSectionsIds)
                                ->get();

        $result = $modules->map(function ($module) use ($sections, $subSections) {

            $moduleSections = $sections->sortBy('order')->where('module_id', $module->id)->map(function ($section) use ($subSections)
            {
                $sectionSubSections = $subSections->where('section_id', $section->id)->sortBy('order');

                $section->subSections = $sectionSubSections->values();

                return $section;
            });

            $module->sections = $moduleSections->values();

            return $module;
        });

        $modules = $result;

        return $modules;
    }

    public function SaveCompany(Request $request)
    {
        $campaigns = Campain::get();
        $id = request('id');
        $name = request('name');
        $contact = request('contact');
        $pais = request('pais');
        $asist_type = request('asist_type');
        $sufijo = request('sufijo');
        $logo = request('logo');

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'svg', 'webp'];

        if (isset($logo)) {
            $file = $logo;

            $mimeType = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();

            if (!in_array($mimeType, $allowedMimeTypes)) {
                return back()->withErrors(['image' => 'El archivo debe ser una imagen válida (JPEG, PNG, GIF, SVG, WebP).']);
            }

            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return back()->withErrors(['image' => 'La extensión del archivo no es válida.']);
            }

            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileExtension = $file->getClientOriginalExtension();

            $uniqueFileName = $fileName . '_' . time() . '.' . $fileExtension;

            $path = $file->storeAs('public/uploads', $uniqueFileName);

            $file = new File();
            $file->name = $uniqueFileName;
            $file->path = $path;
            $file->created_at_user = Auth::user()->name;
            $file->save();

            $logo = $uniqueFileName;
        }

        if (isset($sufijo)) {
            \DB::table('users')->update([
                'email' => \DB::raw("CONCAT(SUBSTRING_INDEX(email, '@', 1), '$sufijo')")
            ]);
        }

        $company = Company::findOrFail($id);
        $company->name = $name;
        $company->contact = $contact;
        $company->pais = $pais;
        $company->asist_type = $asist_type;
        $company->sufijo = $sufijo;

        if (isset($logo)) {
            $company->logo = $logo;
        }

        $company->save();
        $modules = $this->modules();
        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);

        return redirect()->back()->with([
            'company' => $company,
            'campaigns' => $campaigns,
            'modules' => $modules,
            'user' => $user,
        ]);
    }
}