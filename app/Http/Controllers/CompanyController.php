<?php

namespace App\Http\Controllers;

use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {

        $company = Company::findOrFail(1);
        return view('company')->with(compact('company'));
    }

    public function validateForm()
    {
        $messages = [
            'name.required'            => 'Debe ingresar un Nombre.',
            'contact.required'         => 'Debe ingresar un Contacto.',
            'pais.required'            => 'Debe seleccionar un País.',
            'asist_type.required'      => 'Debe seleccionar el Tipo de asistencia.',
            'sufijo.required'          => 'Debe ingresar un Sufijo.'
        ];

        $rules = [
            'name'                     => 'required',
            'contact'                  => 'required',
            'pais'                     => 'required',
            'asist_type'               => 'required',
            'sufijo'                   => 'required'
        ];

        request()->validate($rules, $messages);
        return request()->all();
    }

    public function update()
    {

        $this->validateForm();

        $id = request('id');
        $name = request('name');
        $contact = request('contact');
        $pais = request('pais');
        $asist_type = request('asist_type');
        $sufijo = request('sufijo');

        $element = Company::findOrFail($id);
        $element->name = $name;
        $element->contact = $contact;
        $element->pais = $pais;
        $element->asist_type = $asist_type;
        $element->sufijo = $sufijo;
        $element->save();

        $type = 3;
        $title = 'Bien';
        $msg = 'Los datos se guardaron con éxito.';
        $url = route('dashboard.company.index');


        return response()->json([
            'type'  => $type,
            'title' => $title,
            'msg'   => $msg,
            'url'   => $url
        ]);
    }
}
