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
                        <small>Total cartouches consommées</small>
                        <h4>467</h4>
                        <span class="text-success fw-bold">+3.48% <small>Depuis le mois dernier</small></span>
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
                        <small>Cartouches disponibles</small>
                        <h4>533</h4>
                        <span class="text-danger fw-bold">-3.48% <small>Depuis le mois dernier</small></span>
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
                        <small>Nombre de patients</small>
                        <h4>409</h4>
                        <span class="text-success fw-bold">+3.48% <small>Depuis le mois dernier</small></span>
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
                        <small>Nombre de médecins</small>
                        <h4>84</h4>
                        <span class="text-success fw-bold">+3.48% <small>Depuis le mois dernier</small></span>
                    </div>
                    <div class="text-info fs-3">
                        <i class="bi bi-person-vcard"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mt-5 g-4">
        <!-- Line Chart -->
        <div class="col-md-6">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Consommation des cartouches</h6>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm active">Month</button>
                        <button class="btn btn-outline-primary btn-sm">Week</button>
                    </div>
                </div>
                <canvas id="lineChart" height="200"></canvas>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-md-6">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6>Répartition par Lab</h6>
                    <button class="btn btn-outline-primary btn-sm">Button Label</button>
                </div>
                <div class="d-flex align-items-center">
                    <canvas id="pieChart" style="max-width: 250px;"></canvas>
                    <ul class="list-unstyled ms-4">
                        <li><span class="badge bg-success me-2">&nbsp;</span> Lab 1 - 15%</li>
                        <li><span class="badge bg-danger me-2">&nbsp;</span> Lab 2 - 33%</li>
                        <li><span class="badge bg-info me-2">&nbsp;</span> Lab 3 - 20%</li>
                        <li><span class="badge bg-warning me-2">&nbsp;</span> Lab 4 - 32%</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Line Chart
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Nombre',
                data: [0, 15, 25, 20, 45, 35, 55, 60],
                borderColor: '#6a1b9a',
                backgroundColor: 'rgba(106, 27, 154, 0.2)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6a1b9a'
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Lab 1', 'Lab 2', 'Lab 3', 'Lab 4'],
            datasets: [{
                data: [15, 33, 20, 32],
                backgroundColor: ['#28a745', '#dc3545', '#17a2b8', '#ffc107']
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
