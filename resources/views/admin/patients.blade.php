@extends('admin.sidebar')

@section('main')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-body pb-2">
            <h2 class="card-title mb-4">Patients</h2>
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
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'cin', 'direction' => $sort === 'cin' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'cin' ? 'fw-bold text-primary' : '' }}">
                                    CIN
                                    <span class="ms-1">
                                        @if($sort === 'cin')
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
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'date_naissance', 'direction' => $sort === 'date_naissance' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'date_naissance' ? 'fw-bold text-primary' : '' }}">
                                    Date Naissance
                                    <span class="ms-1">
                                        @if($sort === 'date_naissance')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'genre', 'direction' => $sort === 'genre' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'genre' ? 'fw-bold text-primary' : '' }}">
                                    Genre
                                    <span class="ms-1">
                                        @if($sort === 'genre')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'numero_securite_sociale', 'direction' => $sort === 'numero_securite_sociale' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'numero_securite_sociale' ? 'fw-bold text-primary' : '' }}">
                                    Numero Securite Sociale
                                    <span class="ms-1">
                                        @if($sort === 'numero_securite_sociale')
                                            <i class="bi bi-caret-up{{ $direction === 'asc' ? '-fill text-primary' : '' }}"></i>
                                            <i class="bi bi-caret-down{{ $direction === 'desc' ? '-fill text-primary' : '' }}"></i>
                                        @else
                                            <i class="bi bi-caret-up"></i><i class="bi bi-caret-down"></i>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td class="py-2">{{ $patient->user->name ?? '' }}</td>
                                <td class="py-2">{{ $patient->user->email ?? '' }}</td>
                                <td class="py-2">{{ $patient->nom_complet }}</td>
                                <td class="py-2">{{ $patient->cin }}</td>
                                <td class="py-2">{{ $patient->telephone }}</td>
                                <td class="py-2">{{ $patient->date_naissance }}</td>
                                <td class="py-2">{{ $patient->genre }}</td>
                                <td class="py-2">{{ $patient->numero_securite_sociale }}</td>
                                <td class="py-2">
                                    <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this patient?');">
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
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
