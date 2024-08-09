<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;

class GroupUserController extends Controller
{
    public function index()
    {

        $companies = Company::get();
        $groups = Group::get();
        return view('group_user')->with(compact('groups', 'companies'));
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

    public function store()
    {
        $this->validateForm();

        $id = request('id');
        $company_id = request('company_id');
        $name = request('name');
        $ip = request('ip');

        if (isset($id)) {
            $element = Group::findOrFail($id);
            $msg = 'Grupo actualizado exitosamente.';
        } else {
            $element = new Group();
            $msg = 'Grupo creado exitosamente.';
        }

        $element->company_id = $company_id;
        $element->name = $name;
        $element->ip = $ip;
        $element->save();

        $type = 3;
        $title = 'Bien';
        $url = route('dashboard.group.user.index');


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
        $element = Group::findOrFail($id);
        $element->delete();

        $type = 3;
        $title = 'Bien';
        $msg = 'Grupo eliminado exitosamente.';
        $url = route('dashboard.group.user.index');


        return response()->json([
            'type'  => $type,
            'title' => $title,
            'msg'   => $msg,
            'url'   => $url
        ]);
    }
}
