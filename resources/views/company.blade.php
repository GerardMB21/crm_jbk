@extends('includes.app')

@section('title','Configuración de Empresa')
@section('subtitle','Mi Empresa')

@section('content')
    
    <company-form
        :company = "{{ $company }}"
        :file = "{{ json_encode($file) }}"
        :url="'{{ route('dashboard.company.update') }}'"
        :url_upload="'{{ route('dashboard.sold.upload') }}'"
    ></company-form>

    <loading></loading>

@endsection