@extends('includes.app')

@section('title','Gestión')
@section('subtitle','Usuarios')

@section('content')
    <user-table
    :users = "{{ $users }}"
    :url_delete = "'{{ route('dashboard.user.delete') }}'"
    :url_list_group = "'{{ route('dashboard.user.list.group') }}'"
    >
    </user-table>

    <user-modal
    :company="{{ $company }}"
    :groups="{{ $groups }}"
    :url="' {{ route('dashboard.user.store') }} '"
    ></user-modal>

    <user-modal-group
    :url_delete_group="'{{ route('dashboard.user.delete.group') }}'"
    ></user-modal-group>

    <loading></loading>

@endsection