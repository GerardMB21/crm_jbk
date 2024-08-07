@extends('includes.app')

@section('title','Gestión')
@section('subtitle','Usuarios')

@section('content')
    <user-table
    :users = "{{ $users }}"
    :url_delete = "'{{ route('dashboard.user.delete') }}'"
    >
    </user-table>

    <user-modal
    :url="' {{ route('dashboard.user.store') }} '"
    ></user-modal>

    <loading></loading>

@endsection