@extends('admin.sidebar')

@section('main')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-body pb-2">
            <h2 class="card-title mb-4">Medecins</h2>
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
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'nom_complet', 'direction' => $sort === 'nom_complet' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'nom_complet' ? 'fw-bold text-primary' : '' }}">
                                    Nom Complet
                                    <span class="ms-1">
                                        @if($sort === 'nom_complet')
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
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'numero_cnom', 'direction' => $sort === 'numero_cnom' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'numero_cnom' ? 'fw-bold text-primary' : '' }}">
                                    Numero CNOM
                                    <span class="ms-1">
                                        @if($sort === 'numero_cnom')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'specialite', 'direction' => $sort === 'specialite' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'specialite' ? 'fw-bold text-primary' : '' }}">
                                    Specialite
                                    <span class="ms-1">
                                        @if($sort === 'specialite')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'adresse_cabinet', 'direction' => $sort === 'adresse_cabinet' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'adresse_cabinet' ? 'fw-bold text-primary' : '' }}">
                                    Adresse Cabinet
                                    <span class="ms-1">
                                        @if($sort === 'adresse_cabinet')
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
                            <th class="py-2">Photo Profil</th>
                            <th class="py-2">Statut</th>
                            <th class="py-2">Piece Identite Recto</th>
                            <th class="py-2">Piece Identite Verso</th>
                            <th class="py-2">Diplome</th>
                            <th class="py-2">Attestation CNOM</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medecins as $medecin)
                            <tr>
                                <td class="py-2">{{ $medecin->user->name ?? '' }}</td>
                                <td class="py-2">{{ $medecin->user->email ?? '' }}</td>
                                <td class="py-2">{{ $medecin->nom_complet }}</td>
                                <td class="py-2">{{ $medecin->telephone }}</td>
                                <td class="py-2">{{ $medecin->numero_cnom }}</td>
                                <td class="py-2">{{ $medecin->specialite }}</td>
                                <td class="py-2">{{ $medecin->adresse_cabinet }}</td>
                                <td class="py-2">{{ $medecin->ville }}</td>
                                <td class="py-2">
                                    @if($medecin->photo_profil)
                                        <a href="{{ asset('storage/' . $medecin->photo_profil) }}" download>
                                            <img src="{{ asset('storage/' . $medecin->photo_profil) }}" alt="Photo Profil" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">{{ $medecin->statut }}</td>
                                <td class="py-2">
                                    @if($medecin->piece_identite_recto)
                                        <a href="{{ asset('storage/' . $medecin->piece_identite_recto) }}" download>
                                            <img src="{{ asset('storage/' . $medecin->piece_identite_recto) }}" alt="Recto" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($medecin->piece_identite_verso)
                                        <a href="{{ asset('storage/' . $medecin->piece_identite_verso) }}" download>
                                            <img src="{{ asset('storage/' . $medecin->piece_identite_verso) }}" alt="Verso" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($medecin->diplome)
                                        <a href="{{ asset('storage/' . $medecin->diplome) }}" download>
                                            <img src="{{ asset('storage/' . $medecin->diplome) }}" alt="Diplome" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($medecin->attestation_cnom)
                                        <a href="{{ asset('storage/' . $medecin->attestation_cnom) }}" download>
                                            <img src="{{ asset('storage/' . $medecin->attestation_cnom) }}" alt="Attestation CNOM" class="img-thumbnail" style="max-width: 80px;">
                                        </a>
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    <a href="{{ route('admin.medecins.edit', $medecin->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.medecins.destroy', $medecin->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this medecin?');">
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
                {{ $medecins->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
