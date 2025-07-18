@extends('admin.sidebar')

@section('main')
<div class="container mt-4">
    <h2>Edit Medecin</h2>
    <form action="{{ route('admin.medecins.update', $medecin->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $medecin->user->name ?? '' }}" required>
            </div>
            <div class="col">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $medecin->user->email ?? '' }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Nom Complet</label>
                <input type="text" name="nom_complet" class="form-control" value="{{ $medecin->nom_complet }}">
            </div>
            <div class="col">
                <label>Telephone</label>
                <input type="text" name="telephone" class="form-control" value="{{ $medecin->telephone }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Numero CNOM</label>
                <input type="text" name="numero_cnom" class="form-control" value="{{ $medecin->numero_cnom }}">
            </div>
            <div class="col">
                <label>Specialite</label>
                <input type="text" name="specialite" class="form-control" value="{{ $medecin->specialite }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Adresse Cabinet</label>
                <input type="text" name="adresse_cabinet" class="form-control" value="{{ $medecin->adresse_cabinet }}">
            </div>
            <div class="col">
                <label>Ville</label>
                <input type="text" name="ville" class="form-control" value="{{ $medecin->ville }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Statut</label>
                <input type="text" name="statut" class="form-control" value="{{ $medecin->statut }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('admin.medecins') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
