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

@section('main')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-body pb-2">
            <h2 class="card-title mb-4">Pharmacies</h2>

            <form method="GET" class="mb-3 d-flex justify-content-end align-items-center bg-light rounded-pill px-3 py-2" style="max-width: 400px; margin-left: auto;">
                <span class="me-2 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-0 bg-light rounded-pill" style="box-shadow:none;" placeholder="Search by name or email" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ms-2 rounded-pill px-4">Search</button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 table-striped">
                    <thead class="table-light sticky-top" style="z-index:1;">
                        <tr style="vertical-align: middle;">
                            @php $sort = request('sort', 'id'); $direction = request('direction', 'asc'); @endphp
                            @foreach([
                                'name' => 'Name',
                                'email' => 'Email',
                                'nom_pharmacie' => 'Nom Pharmacie',
                                'telephone' => 'Telephone',
                                'adresse_pharmacie' => 'Adresse Pharmacie',
                                'ville' => 'Ville',
                                'statut' => 'Statut',
                            ] as $key => $label)
                                <th class="py-2">
                                    <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => $key, 'direction' => $sort === $key && $direction === 'asc' ? 'desc' : 'asc'])) }}"
                                       class="text-decoration-none text-dark {{ $sort === $key ? 'fw-bold text-primary' : '' }}">
                                        {{ $label }}
                                        <span class="ms-1">
                                            @if($sort === $key)
                                                <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                                <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                            @else
                                                <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                            @endif
                                        </span>
                                    </a>
                                </th>
                            @endforeach

                            <th class="py-2">Document Justificatif</th>
                            <th class="py-2">Photo</th>
                            <th class="py-2">Pièce Identité Recto</th>
                            <th class="py-2">Pièce Identité Verso</th>
                            <th class="py-2">Diplôme</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pharmacies as $pharmacy)
                            <tr>
                                <td class="py-2">{{ $pharmacy->user->name ?? '' }}</td>
                                <td class="py-2">{{ $pharmacy->user->email ?? '' }}</td>
                                <td class="py-2">{{ $pharmacy->nom_pharmacie }}</td>
                                <td class="py-2">{{ $pharmacy->telephone }}</td>
                                <td class="py-2">{{ $pharmacy->adresse_pharmacie }}</td>
                                <td class="py-2">{{ $pharmacy->ville }}</td>
                                <td class="py-2">{{ $pharmacy->statut }}</td>

                                @foreach([
                                    'document_justificatif_url' => 'Justificatif',
                                    'photo_url' => 'Photo',
                                    'piece_identite_recto' => 'Recto',
                                    'piece_identite_verso' => 'Verso',
                                    'diplome' => 'Diplôme',
                                ] as $fileKey => $label)
                                    <td class="py-2">
                                        @if($pharmacy->$fileKey)
                                            <a href="{{ asset('storage/' . $pharmacy->$fileKey) }}" download>
                                                <img src="{{ asset('storage/' . $pharmacy->$fileKey) }}" alt="{{ $label }}" class="img-thumbnail" style="max-width: 80px;">
                                            </a>
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="py-2">
                                    <a href="{{ route('admin.pharmacies.edit', $pharmacy->id) }}" class="btn btn-sm bg-light border me-1" title="Modifier">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                    <form action="{{ route('admin.pharmacies.destroy', $pharmacy->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this pharmacy?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm bg-light border" title="Supprimer">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $pharmacies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
