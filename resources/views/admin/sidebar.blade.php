{{-- Sidebar layout --}}
@extends('layouts.app')

@section('content')
<style>
    .sidebar {
        min-height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        width: 220px;
        background: #f8f9fa;
        border-right: 1px solid #e0e0e0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .sidebar .nav-link {
        color: #333;
        transition: background 0.2s, color 0.2s;
        border-radius: 4px;
        margin-bottom: 6px;
    }
    .sidebar .nav-link.active, .sidebar .nav-link:hover {
        background: #0d6efd;
        color: #fff;
    }
    .sidebar .sidebar-logo {
        font-size: 2rem;
        color: #0d6efd;
        margin-bottom: 1rem;
    }
    .sidebar .logout-section {
        margin-bottom: 2rem;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
            <div>
                <div class="sidebar-logo text-center my-3">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h4 class="text-center mb-4">Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.medecins') ? ' active' : '' }}" href="{{ route('admin.medecins') }}">
                            <i class="bi bi-person-badge me-2"></i> Medecins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.patients') ? ' active' : '' }}" href="{{ route('admin.patients') }}">
                            <i class="bi bi-people me-2"></i> Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.pharmacies') ? ' active' : '' }}" href="{{ route('admin.pharmacies') }}">
                            <i class="bi bi-capsule-pill me-2"></i> Pharmacies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link{{ request()->routeIs('admin.ordonnances') ? ' active' : '' }}" href="{{ route('admin.ordonnances') }}">
                            <i class="bi bi-file-earmark-medical me-2"></i> Ordonnances
                        </a>
                    </li>
                </ul>
            </div>
            <div class="logout-section text-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">Logout</button>
                </form>
            </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="margin-left:220px;">
            @yield('main')
        </main>
    </div>
</div>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
