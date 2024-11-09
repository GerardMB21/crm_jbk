@extends('layouts.master')
@section('title')
@lang('translation.Boxed_Width')
@endsection
@section('body')

<body class="" data-layout-size="boxed">
    @endsection
    @section('content')
    @component('common-components.breadcrumb')
    @slot('pagetitle') Layouts @endslot
    @slot('title') Boxed Width @endslot
    @endcomponent

   

    @endsection
    @section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>
    @endsection
