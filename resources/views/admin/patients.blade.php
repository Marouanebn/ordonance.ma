@extends('admin.sidebar')

@section('main')
<style>
    .card-title {
        font-weight: 600;
        color: #0d6efd;
    }

    .search-form {
        max-width: 400px;
        margin-left: auto;
        border-radius: 50px;
        background: #f1f3f5;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .search-form input {
        border: none;
        background: transparent;
        flex: 1;
        padding-left: 0.5rem;
        outline: none;
        font-size: 0.95rem;
    }

    .search-form button {
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        font-size: 0.9rem;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .table th a {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .table th i {
        font-size: 0.8rem;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .btn-sm {
        padding: 0.25rem 0.6rem;
        font-size: 0.8rem;
    }

    .card {
        border: none;
        border-radius: 12px;
    }
</style>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title mb-4">Patients</h2>

            <form method="GET" class="search-form mb-4">
                <i class="bi bi-search text-muted"></i>
                <input type="text" name="search" placeholder="Search by name or email" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="sticky-top">
                        <tr>
                            @php $sort = request('sort', 'id'); $direction = request('direction', 'asc'); @endphp
                            @foreach([
                                'name' => 'Name',
                                'email' => 'Email',
                                'nom_complet' => 'Nom Complet',
                                'cin' => 'CIN',
                                'telephone' => 'Téléphone',
                                'date_naissance' => 'Date Naissance',
                                'genre' => 'Genre',
                                'numero_securite_sociale' => 'N° Sécu'
                            ] as $key => $label)
                                <th>
                                    <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => $key, 'direction' => $sort === $key && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                                       class="text-decoration-none {{ $sort === $key ? 'fw-bold text-primary' : 'text-dark' }}">
                                        {{ $label }}
                                        @if($sort === $key)
                                            <i class="bi bi-caret-{{ $direction === 'asc' ? 'up-fill' : 'down-fill' }} text-primary"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr>
                                <td>{{ $patient->user->name ?? '-' }}</td>
                                <td>{{ $patient->user->email ?? '-' }}</td>
                                <td>{{ $patient->nom_complet }}</td>
                                <td>{{ $patient->cin }}</td>
                                <td>{{ $patient->telephone }}</td>
                                <td>{{ $patient->date_naissance }}</td>
                                <td>{{ $patient->genre }}</td>
                                <td>{{ $patient->numero_securite_sociale }}</td>
                                <td>
                                    <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-sm bg-light border me-1" title="Modifier">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                    <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this patient?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm bg-light border" title="Supprimer">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center text-muted py-3">No patients found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
