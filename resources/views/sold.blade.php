@extends('includes.app')

@section('title','Ventas')
@section('subtitle', $campain["name"])

@section('content')

    <sold-tabs
        :campain = "{{ $campain }}"
        :tab_states = "{{ $tab_states }}"
        :url = "'{{ route('dashboard.sold.create') }}'"
        :url_list = "'{{ route('dashboard.sold.list') }}'"
        :url_delete = "'{{ route('dashboard.sold.delete') }}'"
        :url_deshabilitar = "'{{ route('dashboard.sold.deshabilitar') }}'"
        :url_habilitar = "'{{ route('dashboard.sold.habilitar') }}'"
        :url_download = "'{{ route('dashboard.sold.download', ['id' => '__ID__']) }}'"
    ></sold-tabs>

    <loading></loading>

@endsection