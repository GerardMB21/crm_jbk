<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\GroupAdvertisement;
use App\Models\Advertisement;
use App\Models\Logins;

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
        $company = Company::findOrFail(1);

        return view('enterprise', compact('company'));
    }

    public function SaveCompany(Request $request)
    {
        $id = request('id');
        $name = request('name');
        $contact = request('contact');
        $pais = request('pais');
        $asist_type = request('asist_type');
        $sufijo = request('sufijo');
        $menu_color = request('menu_color');
        $text_color = request('text_color');
        $logo = request('logo');

        $company = Company::findOrFail($id);
        $company->name = $name;
        $company->contact = $contact;
        $company->pais = $pais;
        $company->asist_type = $asist_type;
        $company->sufijo = $sufijo;
        $company->menu_color = $menu_color;
        $company->text_color = $text_color;

        $company->save();

        return redirect()->back()->with('company', $company);
    }
}