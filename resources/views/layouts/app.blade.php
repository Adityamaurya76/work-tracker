
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'WorkTrack')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f7fb;
            color: #1e293b;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: .3px;
        }

        .page-container {
            padding-top: 32px;
            padding-bottom: 40px;
        }

        .card {
            border: 1px solid #e9edf4;
            border-radius: 14px;
        }

        .form-control, .form-select {
            padding: 11px 12px;
            border-radius: 8px;
        }

        .btn {
            border-radius: 8px;
        }

        .stat-card {
            transition: transform 0.15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>

    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            @auth
                @if(auth()->user()->role === 'manager')
                    <a class="navbar-brand" href="{{ route('manager.dashboard') }}">
                        <i class="bi bi-clipboard2-check me-1"></i> WorkTrack
                    </a>
                @else
                    <a class="navbar-brand" href="{{ route('employee.dashboard') }}">
                        <i class="bi bi-clipboard2-check me-1"></i> WorkTrack
                    </a>
                @endif
            @else
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="bi bi-clipboard2-check me-1"></i> WorkTrack
                </a>
            @endauth

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    <ul class="navbar-nav me-auto">
                        @if(auth()->user()->role === 'manager')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}"
                                   href="{{ route('manager.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('manager.tasks.*') ? 'active' : '' }}"
                                   href="{{ route('manager.tasks.index') }}">
                                    <i class="bi bi-list-task me-1"></i> Tasks
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('manager.work-updates.*') ? 'active' : '' }}"
                                   href="{{ route('manager.work-updates.index') }}">
                                    <i class="bi bi-journal-text me-1"></i> Work Updates
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('manager.reviews.*') ? 'active' : '' }}"
                                   href="{{ route('manager.reviews.index') }}">
                                    <i class="bi bi-star me-1"></i> Reviews
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}"
                                   href="{{ route('employee.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('employee.tasks.*') ? 'active' : '' }}"
                                   href="{{ route('employee.tasks.index') }}">
                                    <i class="bi bi-list-task me-1"></i> My Tasks
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('employee.work-updates.*') ? 'active' : '' }}"
                                   href="{{ route('employee.work-updates.index') }}">
                                    <i class="bi bi-journal-text me-1"></i> Work Updates
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('employee.reviews.*') ? 'active' : '' }}"
                                   href="{{ route('employee.reviews.index') }}">
                                    <i class="bi bi-star me-1"></i> My Reviews
                                </a>
                            </li>
                        @endif
                    </ul>
                @endauth

                <div class="d-flex align-items-center gap-3">
                    @auth
                        <span class="text-white d-none d-md-inline">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth()->user()->name }}
                            <span class="badge bg-light text-primary ms-1">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </span>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-light btn-sm" type="submit">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container page-container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-1"></i>
                {{ session('error') }}
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show">
                <i class="bi bi-info-circle me-1"></i>
                {{ session('info') }}
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
