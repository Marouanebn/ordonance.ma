@extends('admin.sidebar')

@section('main')
<div class="container mt-4">
    <h2>Edit Pharmacy</h2>
    <form action="{{ route('admin.pharmacies.update', $pharmacy->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $pharmacy->user->name ?? '' }}" required>
            </div>
            <div class="col">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ $pharmacy->user->email ?? '' }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Nom Pharmacie</label>
                <input type="text" name="nom_pharmacie" class="form-control" value="{{ $pharmacy->nom_pharmacie }}">
            </div>
            <div class="col">
                <label>Telephone</label>
                <input type="text" name="telephone" class="form-control" value="{{ $pharmacy->telephone }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Adresse Pharmacie</label>
                <input type="text" name="adresse_pharmacie" class="form-control" value="{{ $pharmacy->adresse_pharmacie }}">
            </div>
            <div class="col">
                <label>Ville</label>
                <input type="text" name="ville" class="form-control" value="{{ $pharmacy->ville }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label>Statut</label>
                <input type="text" name="statut" class="form-control" value="{{ $pharmacy->statut }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('admin.pharmacies') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
