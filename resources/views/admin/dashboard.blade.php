@extends('admin.sidebar')

@section('main')
<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Medecins</h5>
                    <a href="{{ route('admin.medecins') }}" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Patients</h5>
                    <a href="{{ route('admin.patients') }}" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Pharmacies</h5>
                    <a href="{{ route('admin.pharmacies') }}" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Ordonnances</h5>
                    <a href="{{ route('admin.ordonnances') }}" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
