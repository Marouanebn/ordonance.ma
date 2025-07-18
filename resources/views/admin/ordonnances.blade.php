@extends('admin.sidebar')

@section('main')
<style>
    th {
        font-weight: normal !important;
    }

    .table td, .table th {
        vertical-align: middle;
        font-size: 0.95rem;
        padding: 0.6rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
    }

    ul {
        padding-left: 1rem;
    }

    .action-icon {
        font-size: 1.2rem;
        margin-right: 8px;
        transition: color 0.2s ease;
    }

    .action-icon.edit {
        color: #0d6efd;
    }

    .action-icon.edit:hover {
        color: #0b5ed7;
    }

    .action-icon.delete {
        color: #dc3545;
    }

    .action-icon.delete:hover {
        color: #bb2d3b;
    }

    .rounded-pill input {
        font-size: 0.9rem;
    }
</style>

<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-body pb-2">
            <h2 class="card-title mb-4">Ordonnances</h2>

            <form method="GET" class="mb-3 d-flex justify-content-end align-items-center bg-light rounded-pill px-3 py-2" style="max-width: 400px; margin-left: auto;">
                <span class="me-2 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-0 bg-light rounded-pill" placeholder="Search by patient or medecin name" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ms-2 rounded-pill px-4">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 table-striped">
                    <thead class="table-light sticky-top" style="z-index:1;">
                        <tr style="vertical-align: middle;">
                            @php $sort = request('sort', 'id'); $direction = request('direction', 'asc'); @endphp

                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'id', 'direction' => $sort === 'id' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'id' ? 'text-primary' : '' }}">
                                    ID
                                    <span class="ms-1">
                                        @if($sort === 'id')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>

                            <th class="py-2">Patient</th>
                            <th class="py-2">Médecin</th>

                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'date_prescription', 'direction' => $sort === 'date_prescription' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'date_prescription' ? 'text-primary' : '' }}">
                                    Date Prescription
                                    <span class="ms-1">
                                        @if($sort === 'date_prescription')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>

                            <th class="py-2">Détail</th>

                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'status', 'direction' => $sort === 'status' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'status' ? 'text-primary' : '' }}">
                                    Statut
                                    <span class="ms-1">
                                        @if($sort === 'status')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>

                            <th class="py-2">Validée par Pharmacie</th>
                            <th class="py-2">Médicaments</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ordonnances as $ordonnance)
                            <tr>
                                <td class="py-2">{{ $ordonnance->id }}</td>
                                <td class="py-2">{{ $ordonnance->patient->user->name ?? '' }}</td>
                                <td class="py-2">{{ $ordonnance->medecin->user->name ?? '' }}</td>
                                <td class="py-2">{{ $ordonnance->date_prescription }}</td>
                                <td class="py-2">{{ $ordonnance->detail }}</td>
                                <td class="py-2">{{ $ordonnance->status }}</td>
                                <td class="py-2">{{ $ordonnance->validatedByPharmacie->user->name ?? '' }}</td>
                                <td class="py-2">
                                    <ul class="mb-0 ps-3">
                                        @foreach($ordonnance->medicaments as $medicament)
                                            <li>{{ $medicament->nom }} ({{ $medicament->pivot->quantite }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="py-2">
                                    <a href="{{ route('admin.ordonnances.edit', $ordonnance->id) }}" class="action-icon edit" title="Modifier">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.ordonnances.destroy', $ordonnance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this ordonnance?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon delete border-0 bg-transparent p-0" title="Supprimer">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $ordonnances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
