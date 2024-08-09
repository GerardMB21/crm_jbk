@extends('includes.app')

@section('title','Configuración de Empresa')
@section('subtitle','Mi Empresa')

@section('content')
    
    <company-form
    :company = "{{ $company }}"
    :url="'{{ route('dashboard.company.update') }}'"
    ></company-form>

    <loading></loading>

@endsection