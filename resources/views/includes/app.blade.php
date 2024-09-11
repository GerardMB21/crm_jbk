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

<body>
    
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

        let campaniasMenu = document.getElementById('solds-campanias-menu');

        axios.post('/listar-campanias')
            .then(function (response) {

                let campanias = response.data;
                campaniasMenu.innerHTML = ''; 

                campanias.forEach(function(campania) {
                    let listItem = document.createElement('li');
                    let link = document.createElement('a');
                    link.className = 'dropdown-item';
                    link.href = `{{ route('dashboard.sold.index') }}?id=${campania.id}`;
                    link.textContent = campania.name;
                    listItem.appendChild(link);
                    campaniasMenu.appendChild(listItem);
                });
            })
            .catch(function (error) {
                console.log(error);
            });
    </script>

</body>

</html>