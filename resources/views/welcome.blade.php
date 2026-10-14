<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WorkTrack — Modern Employee Work & Performance Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8fafc;
            color: #1e293b;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 80px 0 90px;
            border-radius: 0 0 32px 32px;
        }

        .feature-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.2s ease-in-out;
            background: #ffffff;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .demo-card {
            border-radius: 14px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    {{-- Top Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100 pt-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 text-white" href="{{ url('/') }}">
                <i class="bi bi-clipboard2-check me-2"></i>WorkTrack
            </a>
            <div class="ms-auto d-flex gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-light fw-semibold px-4">
                        <i class="bi bi-speedometer2 me-1"></i> Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Log In
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-light fw-semibold px-3 text-primary">
                        <i class="bi bi-person-plus me-1"></i> Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <header class="hero-section">
        <div class="container text-center pt-5">
            <div class="row justify-content-center pt-4">
                <div class="col-lg-8">
                    <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill mb-3">
                        <i class="bi bi-stars me-1"></i> Streamlined Workflow & Accountability
                    </span>
                    <h1 class="display-4 fw-bold mb-3">
                        Employee WorkTrack System
                    </h1>
                    <p class="lead mb-4 text-white-50">
                        A centralized platform for task delegation, daily progress reporting, blocker transparency, and periodic performance evaluations.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-4 fw-semibold text-primary">
                                Open Dashboard <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 fw-semibold text-primary">
                                Get Started <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                                Create Account
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="container py-5">

        {{-- Quick Demo Credentials Card --}}
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="card demo-card shadow-sm p-4 border-primary border-opacity-25">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-primary-subtle text-primary p-2 me-2">
                            <i class="bi bi-key-fill fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Pre-Configured Demo Accounts</h5>
                            <small class="text-secondary">Use these accounts to test both Manager and Employee experiences</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-primary"><i class="bi bi-shield-lock me-1"></i> Manager Role</strong>
                                    <span class="badge bg-primary">Full Control</span>
                                </div>
                                <div class="small text-secondary mb-1">Email: <code>manager@worktrack.com</code></div>
                                <div class="small text-secondary">Password: <code>password</code></div>
                                <div class="small text-muted mt-2">Can assign tasks, review team updates, submit reviews.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-success"><i class="bi bi-person-workspace me-1"></i> Employee Role</strong>
                                    <span class="badge bg-success">Staff Portal</span>
                                </div>
                                <div class="small text-secondary mb-1">Email: <code>aditya22@gmail.com</code> (or <code>alex@worktrack.com</code>)</div>
                                <div class="small text-secondary">Password: <code>password</code></div>
                                <div class="small text-muted mt-2">Can update task status, submit work updates & blockers.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Feature Highlights --}}
        <div class="text-center mb-4">
            <h2 class="fw-bold">Platform Capabilities</h2>
            <p class="text-secondary">Designed for transparency and seamless collaboration</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="card feature-card h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-primary-subtle text-primary mb-3">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <h5 class="fw-bold">Task Management</h5>
                        <p class="text-secondary small mb-0">
                            Create, assign, categorize by priority, track due dates, and view lifecycle history logs.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card feature-card h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-info-subtle text-info mb-3">
                            <i class="bi bi-speedometer"></i>
                        </div>
                        <h5 class="fw-bold">Status Transitions</h5>
                        <p class="text-secondary small mb-0">
                            Employees seamlessly advance tasks across To-Do, In-Progress, and Completed with full audit logs.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card feature-card h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-warning-subtle text-warning mb-3">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <h5 class="fw-bold">Daily Work Updates</h5>
                        <p class="text-secondary small mb-0">
                            Employees submit work logs, completion percentage, and report roadblocks to prevent bottlenecks.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card feature-card h-100 p-3">
                    <div class="card-body">
                        <div class="feature-icon bg-success-subtle text-success mb-3">
                            <i class="bi bi-star"></i>
                        </div>
                        <h5 class="fw-bold">Performance Reviews</h5>
                        <p class="text-secondary small mb-0">
                            1-5 star ratings with detailed constructive feedback from managers accessible to employees.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    {{-- Footer --}}
    <footer class="border-top py-4 bg-white text-center text-secondary small">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} WorkTrack Application. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
