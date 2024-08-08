@extends('includes.app')

@section('title','Control de Asistencia')
@section('subtitle','Horarios')

@section('content')
    <horario-table
    :horarios = "{{ $horarios }}"
    :url_delete = "'{{ route('dashboard.horario.delete') }}'"
    >
    </horario-table>

    <horario-modal
    :url="' {{ route('dashboard.horario.store') }} '"
    ></horario-modal>


    <loading></loading>

@endsection
