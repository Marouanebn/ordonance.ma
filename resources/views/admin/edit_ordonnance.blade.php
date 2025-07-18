@extends('admin.sidebar')

@section('main')
<div class="container mt-4">
    <h2>Edit Ordonnance</h2>
    <form action="{{ route('admin.ordonnances.update', $ordonnance->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col">
                <label>Date Prescription</label>
                <input type="date" name="date_prescription" class="form-control" value="{{ $ordonnance->date_prescription }}">
            </div>
            <div class="col">
                <label>Status</label>
                <input type="text" name="status" class="form-control" value="{{ $ordonnance->status }}">
            </div>
        </div>
        <div class="mb-3">
            <label>Detail</label>
            <textarea name="detail" class="form-control">{{ $ordonnance->detail }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('admin.ordonnances') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
