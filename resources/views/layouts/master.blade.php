<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.title-meta')
    @include('layouts.head')
</head>

@section('body')

    <body>
    @show

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

<script>
    // Escuchar cambios en el diseño
    function saveBodyAttributes() {
        const body = document.body;
        const attributes = {};
        Array.from(body.attributes).forEach(attr => {
            if (attr.name.startsWith('data-')) {
                attributes[attr.name] = attr.value;
            }
        });
        localStorage.setItem('bodyAttributes', JSON.stringify(attributes));
    }

    // Restaurar los atributos al cargar la página
    function restoreBodyAttributes() {
        const body = document.body;
        const attributes = JSON.parse(localStorage.getItem('bodyAttributes') || '{}');
        Object.entries(attributes).forEach(([key, value]) => {
            if (document.querySelector(`.${key}-${value}`)) document.querySelector(`.${key}-${value}`).checked = true;
            body.setAttribute(key, value);
        });
    }

    // Restaurar al cargar la página
    document.addEventListener('DOMContentLoaded', restoreBodyAttributes);

    // Guardar cada vez que se cambian atributos
    document.querySelectorAll('.form-check-inline .form-check-input').forEach(button => {
        button.addEventListener('click', saveBodyAttributes);
    });
    document.querySelectorAll('.sidebar-setting .form-check-input').forEach(button => {
        button.addEventListener('click', saveBodyAttributes);
    });
</script>

</html>
