<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Horario;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{

    public function index()
    {

        $horarios = Horario::get();


        return view('horario')->with(compact('horarios'));
    }

    public function validateForm()
    {
        $messages = [
            'name.required'         => 'Debe ingresar un nombre.',

        ];

        $rules = [
            'name'                  => 'required',

        ];

        request()->validate($rules, $messages);
        return request()->all();
    }

    public function store()
    {
        $this->validateForm();


        $id = request('id');
        $name = request('name');
        $tolerancia = request('tolerancia');


        if (isset($id)) {
            $element = Horario::findOrFail($id);
            $msg = 'Horario actualizado exitosamente.';
        } else {
            $element = new Horario();
            $msg = 'Horario creado exitosamente.';
        }


        $element->name = $name;
        $element->tolerancia = $tolerancia;

        $element->save();

        $type = 3;
        $title = 'Bien';
        $url = route('dashboard.horario.index');


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
        $element = Horario::findOrFail($id);
        $element->delete();

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
