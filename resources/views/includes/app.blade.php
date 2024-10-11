<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sicacenter</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="min-h-screen position-relative">
    <div class="position-fixed w-100 h-screen d-flex justify-content-center align-items-center" style="z-index: -10; top: 50%; left: 50%; transform: translateX(-50%) translateY(-50%);">
        <img id="root-body" class src="" alt="">
    </div>
    
    @include('includes.navegation')
    
    <div class="container mt-5 bg-white p-4 border rounded">
        <span class="badge bg-dark fs-4 text-white">@yield('title')</span>
        <span>:::</span>
        <span class="badge bg-warning fs-4">@yield('subtitle')</span>
        
    </div>
    
    <main id="app">
        @yield('content')
    </main>
    
    <script src="{{ asset('js/app.js') }}"></script>
    <!--
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>-->
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.js"></script>

    <script>

        new DataTable('#example');

        const bodyRoot = document.getElementById('root-body');
        let menuNavigation = document.getElementById('menu-navigation');

        axios.get('/empresa/ver-logo')
            .then(function (response) {
                const { data } = response;

                if (data) {
                    const fileName = data.path.replace("uploads/","");

                    bodyRoot.src = `http://localhost:8000/empresa/logo/${fileName}`;
                };
            })
            .catch(function (error) {
                console.log(error);
            });

        axios.get('/grupo-usuarios/grupo')
            .then(function (response) {
                const { data } = response;

                if (data) {
                    const { permissions } = data;
                    const permissionsParse = JSON.parse(permissions);

                    const { enterprise_configuration, presence_configuration, administration_configuration, collaborative, campaign_configuration } = permissionsParse;

                    if (enterprise_configuration.length) {
                        let li = document.createElement('li');
                        let a = document.createElement('a');
                        let ul = document.createElement('ul');
                        let ul_li = document.createElement('li');
                        let ul_li_a = document.createElement('a');

                        li.className = 'nav-item dropdown';

                        a.className = 'nav-link dropdown-toggle';
                        a.href = '#';
                        a.textContent = "Configuración de Empresa"
                        a.setAttribute("role", 'button');
                        a.setAttribute("data-bs-toggle", 'dropdown');
                        a.setAttribute("aria-expanded", 'false');

                        ul.className = 'dropdown-menu dropdown-menu-dark';

                        ul_li_a.className = 'dropdown-item';
                        ul_li_a.href = `{{ route('dashboard.company.index') }}`;
                        ul_li_a.textContent = "Mi Empresa";

                        ul_li.appendChild(ul_li_a);
                        ul.appendChild(ul_li);
                        li.appendChild(a);
                        li.appendChild(ul);

                        menuNavigation.appendChild(li);
                    };

                    if (presence_configuration.length) {
                        let li = document.createElement('li');
                        let a = document.createElement('a');
                        let ul = document.createElement('ul');

                        li.className = 'nav-item dropdown';

                        a.className = 'nav-link dropdown-toggle';
                        a.href = '#';
                        a.textContent = "Configuración de Asistencia"
                        a.setAttribute("role", 'button');
                        a.setAttribute("data-bs-toggle", 'dropdown');
                        a.setAttribute("aria-expanded", 'false');

                        ul.className = 'dropdown-menu dropdown-menu-dark';

                        for (let i = 0; i < presence_configuration.length; i++) {
                            const element = presence_configuration[i];

                            let ul_li = document.createElement('li');
                            let ul_li_a = document.createElement('a');

                            if (element == "hours") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.horario.index') }}`;
                                ul_li_a.textContent = "Horarios";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            } else if (element == "disconnection") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.company.index') }}`;
                                ul_li_a.textContent = "Tipos de Desconexión";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            } else if (element == "sedes") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.company.index') }}`;
                                ul_li_a.textContent = "Sedes";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            };
                        };

                        li.appendChild(a);
                        li.appendChild(ul);

                        menuNavigation.appendChild(li);
                    };

                    if (administration_configuration.length) {
                        let li = document.createElement('li');
                        let a = document.createElement('a');
                        let ul = document.createElement('ul');

                        li.className = 'nav-item dropdown';

                        a.className = 'nav-link dropdown-toggle';
                        a.href = '#';
                        a.textContent = "Administración de Usuarios"
                        a.setAttribute("role", 'button');
                        a.setAttribute("data-bs-toggle", 'dropdown');
                        a.setAttribute("aria-expanded", 'false');

                        ul.className = 'dropdown-menu dropdown-menu-dark';

                        for (let i = 0; i < administration_configuration.length; i++) {
                            const element = administration_configuration[i];

                            let ul_li = document.createElement('li');
                            let ul_li_a = document.createElement('a');

                            if (element == "user_groups") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.group.user.index') }}`;
                                ul_li_a.textContent = "Grupos de Usuarios";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            } else if (element == "users") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.user.index') }}`;
                                ul_li_a.textContent = "Usuarios";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            };
                        };

                        li.appendChild(a);
                        li.appendChild(ul);

                        menuNavigation.appendChild(li);
                    };

                    if (collaborative.length) {
                        let li = document.createElement('li');
                        let a = document.createElement('a');
                        let ul = document.createElement('ul');

                        li.className = 'nav-item dropdown';

                        a.className = 'nav-link dropdown-toggle';
                        a.href = '#';
                        a.textContent = "Colaborativo"
                        a.setAttribute("role", 'button');
                        a.setAttribute("data-bs-toggle", 'dropdown');
                        a.setAttribute("aria-expanded", 'false');

                        ul.className = 'dropdown-menu dropdown-menu-dark';

                        for (let i = 0; i < collaborative.length; i++) {
                            const element = collaborative[i];

                            let ul_li = document.createElement('li');
                            let ul_li_a = document.createElement('a');

                            if (element == "advertisements") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.advertisement.index') }}`;
                                ul_li_a.textContent = "Anuncios";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            } else if (element == "popups_welcome") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.company.index') }}`;
                                ul_li_a.textContent = "Popups de Bienvenida";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            };
                        };

                        li.appendChild(a);
                        li.appendChild(ul);

                        menuNavigation.appendChild(li);
                    };

                    if (campaign_configuration.length) {
                        let li = document.createElement('li');
                        let a = document.createElement('a');
                        let ul = document.createElement('ul');

                        li.className = 'nav-item dropdown';

                        a.className = 'nav-link dropdown-toggle';
                        a.href = '#';
                        a.textContent = "Configuración de Campañas"
                        a.setAttribute("role", 'button');
                        a.setAttribute("data-bs-toggle", 'dropdown');
                        a.setAttribute("aria-expanded", 'false');

                        ul.className = 'dropdown-menu dropdown-menu-dark';

                        for (let i = 0; i < campaign_configuration.length; i++) {
                            const element = campaign_configuration[i];

                            let ul_li = document.createElement('li');
                            let ul_li_a = document.createElement('a');

                            if (element == "campaign") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.campain.index') }}`;
                                ul_li_a.textContent = "Campañas";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            } else if (element == "tab_states") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.tab_state.index') }}`;
                                ul_li_a.textContent = "Pestañas de Estado";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            } else if (element == "states") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.state.index') }}`;
                                ul_li_a.textContent = "Estados";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            } else if (element == "blocks") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.block.index') }}`;
                                ul_li_a.textContent = "Bloques de Campos";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            } else if (element == "fields") {

                                ul_li_a.className = 'dropdown-item';
                                ul_li_a.href = `{{ route('dashboard.field.index') }}`;
                                ul_li_a.textContent = "Campos";

                                ul_li.appendChild(ul_li_a);
                                ul.appendChild(ul_li);
                            };
                        };

                        li.appendChild(a);
                        li.appendChild(ul);

                        menuNavigation.appendChild(li);
                    };

                    let li = document.createElement('li');
                    let a = document.createElement('a');
                    let ul = document.createElement('ul');
                    let ul_li = document.createElement('li');
                    let ul_li_a = document.createElement('a');

                    li.className = 'nav-item dropdown';

                    a.className = 'nav-link dropdown-toggle';
                    a.href = '#';
                    a.textContent = "Reportes"
                    a.setAttribute("role", 'button');
                    a.setAttribute("data-bs-toggle", 'dropdown');
                    a.setAttribute("aria-expanded", 'false');

                    ul.className = 'dropdown-menu dropdown-menu-dark';

                    ul_li_a.className = 'dropdown-item';
                    ul_li_a.href = `{{ route('dashboard.user.index') }}`;
                    ul_li_a.textContent = "En construcción";

                    ul_li.appendChild(ul_li_a);
                    ul.appendChild(ul_li);
                    li.appendChild(a);
                    li.appendChild(ul);

                    menuNavigation.appendChild(li);
                };
            })
            .catch(function (error) {
                console.log(error);
            });

        axios.post('/listar-campanias')
            .then(function (response) {

                let campanias = response.data;

                let li = document.createElement('li');
                let a = document.createElement('a');
                let ul = document.createElement('ul');

                li.className = 'nav-item dropdown';

                a.className = 'nav-link dropdown-toggle';
                a.href = '#';
                a.textContent = "Venta"
                a.setAttribute("role", 'button');
                a.setAttribute("data-bs-toggle", 'dropdown');
                a.setAttribute("aria-expanded", 'false');

                ul.className = 'dropdown-menu dropdown-menu-dark';

                campanias.forEach(function(campania) {
                    let listItem = document.createElement('li');
                    let link = document.createElement('a');


                    link.className = 'dropdown-item';
                    link.href = `{{ route('dashboard.sold.index') }}?id=${campania.id}`;
                    link.textContent = campania.name;
                    listItem.appendChild(link);
                    ul.appendChild(listItem);
                });

                li.appendChild(a);
                li.appendChild(ul);

                menuNavigation.appendChild(li);
            })
            .catch(function (error) {
                console.log(error);
            });
    </script>

</body>

</html>