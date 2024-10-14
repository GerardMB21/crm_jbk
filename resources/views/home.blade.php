@extends('includes.app')

@section('title','Inicio')

@section('content')

    <div class="container mt-2 border rounded bg-white p-0">
        <home-tabs></home-tabs>
        <home-content
            :user="{{ $user }}"
            :group="{{ $group }}"
            :advertisements="{{ $advertisements }}"
            :logins="{{ $logins }}"
            :url_download="'{{ route('dashboard.sold.download', ['id' => '__ID__']) }}'"
        ></home-content>
    </div>

    <loading></loading>

@endsection