@extends('admin.sidebar')

@section('main')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-body pb-2">
            <h2 class="card-title mb-4">Ordonnances</h2>
            <form method="GET" class="mb-3 d-flex justify-content-end align-items-center bg-light rounded-pill px-3 py-2" style="max-width: 400px; margin-left: auto;">
                <span class="me-2 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-0 bg-light rounded-pill" style="box-shadow:none;" placeholder="Search by patient or medecin name" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary ms-2 rounded-pill px-4">Search</button>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 table-striped">
                    <thead class="table-light sticky-top" style="z-index:1;">
                        <tr style="vertical-align: middle;">
                            @php $sort = request('sort', 'id'); $direction = request('direction', 'asc'); @endphp
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'id', 'direction' => $sort === 'id' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'id' ? 'fw-bold text-primary' : '' }}">
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
                            <th class="py-2">Medecin</th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'date_prescription', 'direction' => $sort === 'date_prescription' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'date_prescription' ? 'fw-bold text-primary' : '' }}">
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
                            <th class="py-2">Detail</th>
                            <th class="py-2">
                                <a href="?{{ http_build_query(array_merge(request()->except('page'), ['sort' => 'status', 'direction' => $sort === 'status' && $direction === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark {{ $sort === 'status' ? 'fw-bold text-primary' : '' }}">
                                    Status
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
                            <th class="py-2">Validated By Pharmacie</th>
                            <th class="py-2">Medicaments</th>
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
                                    <a href="{{ route('admin.ordonnances.edit', $ordonnance->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.ordonnances.destroy', $ordonnance->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this ordonnance?');">
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
                {{ $ordonnances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
