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

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class HoursController extends Controller
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
      $horarios = Horario::with(['days' => function ($query) {
                              $query->select('horario_id', 'day', 'inicio', 'final');
                          }])
                          ->select('id', 'name', 'sede_id', 'tolerancia_min', 'state')
                          ->get();

      $horarios = $horarios->map(function ($horario) {
                      return [
                          'id' => $horario->id,
                          'name' => $horario->name,
                          'sede_id' => $horario->sede_id,
                          'tolerancia_min' => $horario->tolerancia_min,
                          'state' => $horario->state,
                          'days' => $horario->days->map(function ($day) {
                              return [
                                  'day' => $day->day,
                                  'inicio' => $day->inicio,
                                  'final' => $day->final
                              ];
                          })
                      ];
                  });

        $horarios = json_decode(json_encode($horarios));

        return view('hours', compact('horarios'));
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