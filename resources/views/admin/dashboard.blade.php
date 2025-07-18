@extends('admin.sidebar')

@section('main')
<div class="container mt-4">
    <h2 class="mb-4">Tableau de bord</h2>

    <!-- Cartes Statistiques -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small>Total patient</small>
                        <h4>{{ isset($totalPatients) ? $totalPatients : 0 }}</h4>
                    </div>
                    <div class="text-success fs-3">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small>Total medecins</small>
                        <h4>{{ isset($totalMedecins) ? $totalMedecins : 0 }}</h4>
                    </div>
                    <div class="text-danger fs-3">
                        <i class="bi bi-graph-down-arrow"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small>total pharmacies</small>
                        <h4>{{ isset($totalPharmacies) ? $totalPharmacies : 0 }}</h4>
                    </div>
                    <div class="text-warning fs-3">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small>total ordonance</small>
                        <h4>{{ isset($totalOrdonnances) ? $totalOrdonnances : 0 }}</h4>
                    </div>
                    <div class="text-info fs-3">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


