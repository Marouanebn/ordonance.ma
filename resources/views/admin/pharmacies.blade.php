@extends('admin.sidebar')

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
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'name', 'direction' => $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'name' ? 'fw-bold text-primary' : '' }}">
                                    Name
                                    <span class="ms-1">
                                        @if($sort === 'name')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'email', 'direction' => $sort === 'email' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'email' ? 'fw-bold text-primary' : '' }}">
                                    Email
                                    <span class="ms-1">
                                        @if($sort === 'email')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'nom_pharmacie', 'direction' => $sort === 'nom_pharmacie' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'nom_pharmacie' ? 'fw-bold text-primary' : '' }}">
                                    Nom Pharmacie
                                    <span class="ms-1">
                                        @if($sort === 'nom_pharmacie')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'telephone', 'direction' => $sort === 'telephone' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'telephone' ? 'fw-bold text-primary' : '' }}">
                                    Telephone
                                    <span class="ms-1">
                                        @if($sort === 'telephone')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'adresse_pharmacie', 'direction' => $sort === 'adresse_pharmacie' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'adresse_pharmacie' ? 'fw-bold text-primary' : '' }}">
                                    Adresse Pharmacie
                                    <span class="ms-1">
                                        @if($sort === 'adresse_pharmacie')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'ville', 'direction' => $sort === 'ville' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'ville' ? 'fw-bold text-primary' : '' }}">
                                    Ville
                                    <span class="ms-1">
                                        @if($sort === 'ville')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'statut', 'direction' => $sort === 'statut' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'statut' ? 'fw-bold text-primary' : '' }}">
                                    Statut
                                    <span class="ms-1">
                                        @if($sort === 'statut')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">Document Justificatif</th>
                            <th class="py-2">Photo</th>
                            <th class="py-2">Piece Identite Recto</th>
                            <th class="py-2">Piece Identite Verso</th>
                            <th class="py-2">Diplome</th>
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
                                <td class="py-2">
                                    @if($pharmacy->document_justificatif_url)
                                        <a href="{{ asset('storage/' . $pharmacy->document_justificatif_url) }}" download>
                                            <img src="{{ asset('storage/' . $pharmacy->document_justificatif_url) }}" alt="Justificatif" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($pharmacy->photo_url)
                                        <a href="{{ asset('storage/' . $pharmacy->photo_url) }}" download>
                                            <img src="{{ asset('storage/' . $pharmacy->photo_url) }}" alt="Photo" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($pharmacy->piece_identite_recto)
                                        <a href="{{ asset('storage/' . $pharmacy->piece_identite_recto) }}" download>
                                            <img src="{{ asset('storage/' . $pharmacy->piece_identite_recto) }}" alt="Recto" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($pharmacy->piece_identite_verso)
                                        <a href="{{ asset('storage/' . $pharmacy->piece_identite_verso) }}" download>
                                            <img src="{{ asset('storage/' . $pharmacy->piece_identite_verso) }}" alt="Verso" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($pharmacy->diplome)
                                        <a href="{{ asset('storage/' . $pharmacy->diplome) }}" download>
                                            <img src="{{ asset('storage/' . $pharmacy->diplome) }}" alt="Diplome" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    <a href="{{ route('admin.pharmacies.edit', $pharmacy->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.pharmacies.destroy', $pharmacy->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this pharmacy?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
