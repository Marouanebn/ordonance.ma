@extends('layouts.app')

@section('content')
<style>
    .sidebar {
        min-height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        width: 240px;
        background: #ffffff;
        border-right: 1px solid #e5e7eb;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.5rem 1rem;
    }

    .sidebar-logo {
        font-size: 2rem;
        color: #0d6efd;
        margin-bottom: 0.5rem;
    }

    .sidebar h4 {
        font-size: 1.25rem;
        color: #343a40;
        margin-bottom: 2rem;
        font-weight: 600;
    }

    .sidebar .nav-link {
        color: #495057;
        display: flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        font-size: 0.95rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .sidebar .nav-link i {
        font-size: 1.1rem;
        margin-right: 0.5rem;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }

    .logout-section {
        margin-top: 2rem;
    }

    .logout-section .btn {
        font-size: 0.9rem;
    }

    main {
        margin-left: 240px;
        padding: 2rem;
        background: #f8f9fa;
        min-height: 100vh;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <nav class="sidebar">
            <div>
                <div class="sidebar-logo text-center">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h4 class="text-center">Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.medecins') ? ' active' : '' }}" href="{{ route('admin.medecins') }}">
                            <i class="bi bi-person-badge"></i> Médecins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.patients') ? ' active' : '' }}" href="{{ route('admin.patients') }}">
                            <i class="bi bi-people"></i> Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.pharmacies') ? ' active' : '' }}" href="{{ route('admin.pharmacies') }}">
                            <i class="bi bi-capsule-pill"></i> Pharmacies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.ordonnances') ? ' active' : '' }}" href="{{ route('admin.ordonnances') }}">
                            <i class="bi bi-file-earmark-medical"></i> Ordonnances
                        </a>
                    </li>
                </ul>
            </div>

            <div class="logout-section">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <main role="main" class="col">
            @yield('main')
        </main>
    </div>
</div>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
