@extends('admin.sidebar')

@section('main')
<div class="container mt-4">
    <h2>Edit Patient</h2>
    <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $patient->user->name ?? '' }}" required>
            </div>
            <div class="col">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $patient->user->email ?? '' }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Nom Complet</label>
                <input type="text" name="nom_complet" class="form-control" value="{{ $patient->nom_complet }}">
            </div>
            <div class="col">
                <label>CIN</label>
                <input type="text" name="cin" class="form-control" value="{{ $patient->cin }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Telephone</label>
                <input type="text" name="telephone" class="form-control" value="{{ $patient->telephone }}">
            </div>
            <div class="col">
                <label>Date Naissance</label>
                <input type="date" name="date_naissance" class="form-control" value="{{ $patient->date_naissance }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Genre</label>
                <input type="text" name="genre" class="form-control" value="{{ $patient->genre }}">
            </div>
            <div class="col">
                <label>Numero Securite Sociale</label>
                <input type="text" name="numero_securite_sociale" class="form-control" value="{{ $patient->numero_securite_sociale }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('admin.patients') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
