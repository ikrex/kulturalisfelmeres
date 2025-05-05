<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Kulturális Kutatás Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            background-size: cover;
        }
        .sidebar-brand {
            height: 4.375rem;
            padding: 1.5rem 1rem;
            font-size: 1.2rem;
            font-weight: 800;
            text-transform: uppercase;
            color: white;
        }
        .sidebar-link {
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.2s;
            display: block;
        }
        .sidebar-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar-link.active {
            color: white;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.2);
        }
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        .font-weight-bold {
            font-weight: 700 !important;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 px-0 sidebar">
                <div class="sidebar-brand">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none text-white">
                        <i class="fas fa-chart-line me-2"></i>
                        Admin Panel
                    </a>
                </div>
                <hr class="bg-white border-1 my-3 opacity-25">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-tachometer-alt me-2"></i>
                            Vezérlőpult
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.surveys') }}" class="sidebar-link {{ request()->routeIs('admin.surveys*') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-clipboard-check me-2"></i>
                            Kérdőívek
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.temporary-surveys') }}" class="sidebar-link {{ request()->routeIs('admin.temporary-surveys*') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-clipboard-list me-2"></i>
                            Folyamatban lévő
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-users me-2"></i>
                            Felhasználók
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.statistics') }}" class="sidebar-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-chart-bar me-2"></i>
                            Statisztikák
                        </a>
                    </li>

                    <hr class="bg-white border-1 my-3 opacity-25">
                    <div class="d-flex align-items-center text-decoration-none text-white">Kulturális intézmények</div>
                    <a class="sidebar-link {{ Request::routeIs('admin.institutions.*') ? 'active' : '' }}" href="{{ route('admin.institutions.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-university"></i></div>
                        Intézmények
                    </a>
                    <a class="sidebar-link {{ Request::routeIs('admin.institutions.upload') ? 'active' : '' }}" href="{{ route('admin.institutions.upload') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-upload"></i></div>
                        Excel importálása
                    </a>

                    <div class="sb-sidenav-menu-heading">Email kezelés</div>
                    <a class="sidebar-link {{ Request::routeIs('admin.emails.*') ? 'active' : '' }}" href="{{ route('admin.emails.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                        Email küldés
                    </a>


                    <li class="nav-item">
                        <a href="{{ route('admin.settings') }}" class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-cog me-2"></i>
                            Beállítások
                        </a>
                    </li>
                    <hr class="bg-white border-1 my-3 opacity-25">
                    <li class="nav-item">
                        <a href="/" class="sidebar-link">
                            <i class="fas fa-fw fa-arrow-left me-2"></i>
                            Vissza a főoldalra
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-link bg-transparent border-0 w-100 text-start">
                                <i class="fas fa-fw fa-sign-out-alt me-2"></i>
                                Kijelentkezés
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 ms-auto px-4">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">
                    <div class="container-fluid">
                        <!-- Toggle Sidebar Button (for mobile) -->
                        <button class="btn btn-link d-md-none rounded-circle me-3">
                            <i class="fa fa-bars"></i>
                        </button>

                        <!-- Page Title -->
                        <h1 class="h4 mb-0 text-gray-800">@yield('title')</h1>

                        <!-- Right aligned items -->
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-user-circle fa-fw"></i>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                            Kijelentkezés
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- Alert Messages -->
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

    <!-- Custom JS -->
    @yield('scripts')
</body>
</html>
