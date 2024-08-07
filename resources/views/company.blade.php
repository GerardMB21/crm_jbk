@extends('includes.app')

@section('title','Gestión')
@section('subtitle','Mi Empresa')

@section('content')
    
    <company-form
    :company = "{{ $company }}"
    :url="'{{ route('dashboard.company.update') }}'"
    ></company-form>

    <loading></loading>

@endsection