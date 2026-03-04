<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Management System')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-custom {
            background: var(--primary-gradient);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: var(--primary-gradient);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            font-weight: 600;
        }
        
        .btn-custom {
            background: var(--primary-gradient);
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .stats-card {
            padding: 20px;
            border-radius: 15px;
            color: white;
            margin-bottom: 20px;
        }
        
        .stats-card.primary {
            background: var(--primary-gradient);
        }
        
        .stats-card.success {
            background: var(--success-gradient);
        }
        
        .stats-card.warning {
            background: var(--warning-gradient);
        }
        
        .stats-card i {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .stats-card h2 {
            margin: 10px 0;
            font-weight: 700;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .avatar-lg {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }
        
        .table-custom thead {
            background: var(--primary-gradient);
            color: white;
        }
        
        .alert-custom {
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .footer {
            background: white;
            padding: 20px 0;
            margin-top: 50px;
            box-shadow: 0 -5px 15px rgba(0,0,0,0.05);
        }
        
        /* Dark mode styles */
        body.dark-mode {
            background: #1a1a1a;
            color: #fff;
        }
        
        body.dark-mode .card {
            background: #2d2d2d;
            color: #fff;
        }
        
        body.dark-mode .sidebar {
            background: #2d2d2d;
        }
        
        body.dark-mode .sidebar .nav-link {
            color: #fff;
        }
        
        body.dark-mode .table-custom {
            background: #2d2d2d;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>
                Student Management System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button class="btn btn-sm btn-outline-light me-2" onclick="toggleDarkMode()">
                            <i class="bi bi-moon-stars-fill"></i>
                        </button>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <div class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                        <i class="bi bi-people"></i> Students
                    </a>
                    <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                        <i class="bi bi-book"></i> Courses
                    </a>
                    <a class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                        <i class="bi bi-calendar-check"></i> Attendance
                    </a>
                    <a class="nav-link {{ request()->routeIs('fees.*') ? 'active' : '' }}" href="{{ route('fees.index') }}">
                        <i class="bi bi-cash"></i> Fees
                    </a>
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="bi bi-file-text"></i> Reports
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-custom" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show alert-custom" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">
                <i class="bi bi-c-circle me-1"></i>
                {{ date('Y') }} Student Management System. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Dark mode toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        }

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }

        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                pageLength: 10,
                responsive: true
            });
        });

        // Smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>