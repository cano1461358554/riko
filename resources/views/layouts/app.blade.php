<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CFE Sistema de Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: {{ request()->is('/') || request()->is('login*') || request()->is('register*') ? '0' : '70px' }};
        }

        .navbar-cfe {
            background-color: #00573F;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: {{ request()->is('/') || request()->is('login*') || request()->is('register*') ? 'none' : 'block' }} !important;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .nav-link {
            color: #FFFFFF !important;
            padding: 10px 15px;
            transition: background-color 0.3s ease;
        }

        .nav-link:hover {
            background-color: #003D2C;
            border-radius: 4px;
        }

        .dropdown-menu {
            background-color: #007A5E;
            border: none;
        }

        .dropdown-item {
            color: #FFFFFF;
        }

        .dropdown-item:hover {
            background-color: #00573F;
        }

        .content {
            padding: 20px;
            background-color: #FFFFFF;
            min-height: calc(100vh - {{ request()->is('/') || request()->is('login*') || request()->is('register*') ? '0' : '70px' }});
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #00573F;
            color: #FFFFFF;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: #e9ecef;
        }

        .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255, 255, 255, 0.8)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }

        .active-menu-item {
            background-color: #003D2C;
            border-radius: 4px;
        }

        /* ========== ESTILOS PARA EL PERFIL ========== */
        .profile-overlay {
            position: fixed;
            top: 70px;
            right: -40%;
            width: 40%;
            height: calc(100vh - 70px);
            background-color: white;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
            transition: right 0.3s ease;
            overflow-y: auto;
            padding: -30px;
        }

        .profile-overlay.active {
            right: 0;
        }

        .overlay-backdrop {
            position: fixed;
            top: 70px;
            left: 0;
            width: 100%;
            height: calc(100vh - 70px);
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
            display: none;
        }

        .profile-overlay.active ~ .overlay-backdrop {
            display: block;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .profile-header h4 {
            color: #00573F;
            margin: 0;
        }

        .profile-close-btn {
            font-size: 1.2rem;
            color: #6c757d;
        }

        .profile-close-btn:hover {
            color: #00573F;
        }
    </style>
</head>
<body>

<!-- Barra de navegación superior -->
@unless(request()->is('/') || request()->is('login*') || request()->is('register*'))
    @php
        $user = auth()->user();
        $isEmpleado = $user->hasRole('empleado');
    @endphp

    <nav class="navbar navbar-expand-lg navbar-dark navbar-cfe">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">CFE Inventario</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mr-auto">
                    <!-- Menú Movimientos -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('prestamos*') || request()->is('ingresos*') || (!$isEmpleado && request()->is('resguardos*')) ? 'active-menu-item' : '' }}"
                           href="#" id="movimientosDropdown" data-toggle="dropdown">
                            <i class="fas fa-exchange-alt"></i> Movimientos
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item {{ request()->is('prestamos*') ? 'active' : '' }}"
                               href="{{ route('prestamos.index') }}">Préstamos</a>
                            @role('admin|encargado')

                            <a class="dropdown-item {{ request()->is('ingresos*') ? 'active' : '' }}"
                               href="{{ route('ingresos.index') }}">Ingresos</a>
                            @if(!$isEmpleado)
                                {{--                                <a class="dropdown-item {{ request()->is('resguardos*') ? 'active' : '' }}"--}}
                                {{--                                   href="{{ route('resguardos.index') }}">Resguardos</a>--}}
                            @endif
                        </div>
                        @endrole

                    </li>

                    <!-- Menú Materiales -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('materials*') || (!$isEmpleado && (request()->is('stocks*') || request()->is('almacens*'))) ? 'active-menu-item' : '' }}"
                           href="#" id="materialesDropdown" data-toggle="dropdown">
                            <i class="fas fa-boxes"></i> Materiales
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item {{ request()->is('materials*') ? 'active' : '' }}"
                               href="{{ route('materials.index') }}">Materiales</a>
                            @if(!$isEmpleado)

                                @role('admin|encargado')
                                <a class="dropdown-item {{ request()->is('stocks*') ? 'active' : '' }}"
                                   href="{{ route('stocks.index') }}">Stocks</a>
                                <a class="dropdown-item {{ request()->is('almacens*') ? 'active' : '' }}"
                                   href="{{ route('almacens.index') }}">Almacenes</a>
                                @endrole


                            @endif
                        </div>
                    </li>

                    <!-- Menú Administración -->
                    @if(!$isEmpleado)
                        @role('admin|encargado')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->is('users*') ? 'active-menu-item' : '' }}"
                               href="#" id="adminDropdown" data-toggle="dropdown">
                                <i class="fas fa-users-cog"></i> Administración
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item {{ request()->is('users*') ? 'active' : '' }}"
                                   href="{{ route('users.index') }}">Usuarios</a>
                            </div>
                        </li>
                        @endrole


                    @endif
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <span class="nav-link text-white">
                            <i class="fas fa-user-tag"></i> {{ ucfirst($user->roles->first()->name ?? 'Empleado') }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="showProfile">
                            <i class="fas fa-user-circle"></i> Perfil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endunless

<!-- Formulario de logout oculto -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Overlay del Perfil -->
<div class="profile-overlay" id="profileOverlay">
    <!-- Contenido cargado dinámicamente via AJAX -->
</div>

<!-- Backdrop para el overlay -->
<div class="overlay-backdrop" id="overlayBackdrop"></div>

<!-- Contenido principal -->
<div class="content">
    @yield('content')
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        // Mostrar/ocultar perfil
        $('#showProfile').click(function(e) {
            e.preventDefault();

            // Cargar contenido del perfil via AJAX
            $.get('{{ route("perfil.show") }}', function(data) {
                $('#profileOverlay').html(data).addClass('active');
            });
        });

        // Cerrar perfil
        $(document).on('click', '#overlayBackdrop, .profile-close-btn', function() {
            $('#profileOverlay').removeClass('active');
        });

        // Cerrar con tecla ESC
        $(document).keyup(function(e) {
            if (e.key === "Escape") {
                $('#profileOverlay').removeClass('active');
            }
        });

        // Manejo de menús activos
        $('.dropdown-item').filter(function() {
            return this.href == location.href.replace(/#.*/, "");
        }).addClass('active').closest('.dropdown-menu').prev().addClass('active-menu-item');

        // Mostrar navbar en páginas protegidas
        if(!(window.location.pathname === '/' ||
            window.location.pathname === '/home' ||
            window.location.pathname.startsWith('/login') ||
            window.location.pathname.startsWith('/register'))) {
            $('.navbar-cfe').show();
            $('body').css('padding-top', '70px');
            $('.content').css('min-height', 'calc(100vh - 70px)');
        }
    });
</script>

</body>
</html>
