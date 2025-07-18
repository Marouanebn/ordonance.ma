@extends('admin.sidebar')

@section('main')
<style>
    .table td, .table th {
        vertical-align: middle;
        font-size: 0.95rem;
        padding: 0.6rem;
    }

    th {
        font-weight: normal !important;
    }

    .img-thumbnail {
        max-height: 60px;
        object-fit: cover;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .action-icons i {
        cursor: pointer;
        transition: color 0.2s;
    }

    .action-icons i:hover {
        opacity: 0.8;
    }
</style>


<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-body pb-2">
            <h2 class="card-title mb-4">Médecins</h2>

            <form method="GET" class="mb-3 d-flex justify-content-end align-items-center bg-light rounded-pill px-3 py-2" style="max-width: 400px; margin-left: auto;">
                <span class="me-2 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-0 bg-light rounded-pill" style="box-shadow:none;" placeholder="Search by name or email" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ms-2 rounded-pill px-4">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 table-striped">
                    <thead class="table-light sticky-top">
                        <tr>
                            @php $sort = request('sort', 'id'); $direction = request('direction', 'asc'); @endphp

                            {{-- Sortable headers (unchanged) --}}
                            @foreach([
                                'name' => 'Name',
                                'email' => 'Email',
                                'nom_complet' => 'Nom Complet',
                                'telephone' => 'Téléphone',
                                'numero_cnom' => 'Numéro CNOM',
                                'specialite' => 'Spécialité',
                                'adresse_cabinet' => 'Adresse Cabinet',
                                'ville' => 'Ville',
                            ] as $field => $label)
                                <th>
                                    <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => $field, 'direction' => $sort === $field && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                                       class="text-decoration-none text-dark {{ $sort === $field ? 'fw-bold text-primary' : '' }}">
                                        {{ $label }}
                                        <span class="ms-1">
                                            <i class="bi bi-caret-up{{ $sort === $field && $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $sort === $field && $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        </span>
                                    </a>
                                </th>
                            @endforeach

                            <th>Photo Profil</th>
                            <th>Statut</th>
                            <th>Pièce ID Recto</th>
                            <th>Pièce ID Verso</th>
                            <th>Diplôme</th>
                            <th>Attestation CNOM</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medecins as $medecin)
                            <tr>
                                <td>{{ $medecin->user->name ?? '' }}</td>
                                <td>{{ $medecin->user->email ?? '' }}</td>
                                <td>{{ $medecin->nom_complet }}</td>
                                <td>{{ $medecin->telephone }}</td>
                                <td>{{ $medecin->numero_cnom }}</td>
                                <td>{{ $medecin->specialite }}</td>
                                <td>{{ $medecin->adresse_cabinet }}</td>
                                <td>{{ $medecin->ville }}</td>

                                {{-- Image cells --}}
                                @foreach([
                                    'photo_profil' => 'Profil',
                                    'piece_identite_recto' => 'Recto',
                                    'piece_identite_verso' => 'Verso',
                                    'diplome' => 'Diplôme',
                                    'attestation_cnom' => 'CNOM',
                                ] as $key => $label)
                                    <td>
                                        @if($medecin->$key)
                                            <a href="{{ asset('storage/' . $medecin->$key) }}" download>
                                                <img src="{{ asset('storage/' . $medecin->$key) }}" alt="{{ $label }}" class="img-thumbnail">
                                            </a>
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td>{{ $medecin->statut }}</td>

                                {{-- Action icons --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 action-icons">
                                        <a href="{{ route('admin.medecins.edit', $medecin->id) }}" class="text-primary" title="Edit">
                                            <i class="bi bi-pencil-square fs-5"></i>
                                        </a>
                                        <form action="{{ route('admin.medecins.destroy', $medecin->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this médecin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn p-0 text-danger border-0 bg-transparent" title="Delete">
                                                <i class="bi bi-trash fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $medecins->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
