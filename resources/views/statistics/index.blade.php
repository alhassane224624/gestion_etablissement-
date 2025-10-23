@extends('layouts.app')

@section('title', 'Statistiques Avancées')
@section('page-title', 'Tableau de Bord Statistiques')

@section('content')
    <!-- Statistiques générales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-icon mx-auto mb-2" style="background: var(--primary-color);">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="mb-1">{{ $stats['general']['total_stagiaires'] }}</h3>
                <p class="text-muted mb-0">Stagiaires Total</p>
                <small class="text-success">
                    +{{ $stats['general']['stagiaires_ce_mois'] }} ce mois
                </small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-icon mx-auto mb-2" style="background: var(--success-color);">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="mb-1">{{ $stats['general']['total_filieres'] }}</h3>
                <p class="text-muted mb-0">Filières</p>
                <small class="text-info">Actives</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-icon mx-auto mb-2" style="background: var(--warning-color);">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 class="mb-1">{{ $stats['general']['total_professeurs'] }}</h3>
                <p class="text-muted mb-0">Professeurs</p>
                <small class="text-primary">Actifs</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-icon mx-auto mb-2" style="background: var(--danger-color);">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="mb-1">{{ $stats['general']['total_notes'] }}</h3>
                <p class="text-muted mb-0">Notes Saisies</p>
                <small class="text-info">
                    Moy: {{ number_format($stats['general']['moyenne_generale'], 2) }}/20
                </small>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="action-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-download me-2"></i>Exports et Rapports
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('statistics.export', ['format' => 'excel']) }}" class="btn btn-success">
                            <i class="fas fa-file-excel me-2"></i>Excel
                        </a>
                        <a href="{{ route('statistics.export', ['format' => 'pdf']) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf me-2"></i>PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par filière -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="action-card">
                <h5 class="mb-3">
                    <i class="fas fa-chart-bar me-2"></i>Performances par Filière
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Filière</th>
                                <th>Niveau</th>
                                <th class="text-center">Stagiaires</th>
                                <th class="text-center">Notes</th>
                                <th class="text-center">Moyenne</th>
                                <th class="text-center">Taux Réussite</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['filieres'] as $filiere)
                                <tr>
                                    <td><strong>{{ $filiere['nom'] }}</strong></td>
                                    <td>{{ $filiere['niveau'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $filiere['stagiaires_count'] }}</span>
                                    </td>
                                    <td class="text-center">{{ $filiere['notes_count'] }}</td>
                                    <td class="text-center">
                                        @if($filiere['moyenne'])
                                            <span class="badge bg-{{ $filiere['moyenne'] >= 10 ? 'success' : 'danger' }}">
                                                {{ number_format($filiere['moyenne'], 2) }}/20
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $filiere['taux_reussite'] >= 80 ? 'success' : ($filiere['taux_reussite'] >= 60 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $filiere['taux_reussite'] }}%">
                                                {{ number_format($filiere['taux_reussite'], 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="action-card">
                <h5 class="mb-3">
                    <i class="fas fa-chart-pie me-2"></i>Répartition des Mentions
                </h5>
                <canvas id="mentionsChart" width="300" height="300"></canvas>
                <div class="mt-3">
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-success">Excellent (≥16)</small>
                            <h6>{{ $stats['notes']['distribution']['excellent'] }}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-primary">Bien (14-16)</small>
                            <h6>{{ $stats['notes']['distribution']['bien'] }}</h6>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-warning">Assez Bien (12-14)</small>
                            <h6>{{ $stats['notes']['distribution']['assez_bien'] }}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-danger">Insuffisant (<10)</small>
                            <h6>{{ $stats['notes']['distribution']['insuffisant'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par matière -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="action-card">
                <h5 class="mb-3">
                    <i class="fas fa-book me-2"></i>Performances par Matière
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Matière</th>
                                <th class="text-center">Total Notes</th>
                                <th class="text-center">Moyenne</th>
                                <th class="text-center">Note Max</th>
                                <th class="text-center">Note Min</th>
                                <th class="text-center">Réussites</th>
                                <th class="text-center">Graphique</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['notes']['par_matiere'] as $matiere)
                                @php
                                    $pourcentageReussite = $matiere->total > 0 ? ($matiere->reussites / $matiere->total) * 100 : 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $matiere->matiere }}</strong></td>
                                    <td class="text-center">{{ $matiere->total }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $matiere->moyenne >= 10 ? 'success' : 'danger' }}">
                                            {{ number_format($matiere->moyenne, 2) }}/20
                                        </span>
                                    </td>
                                    <td class="text-center text-success">{{ number_format($matiere->note_max, 2) }}</td>
                                    <td class="text-center text-danger">{{ number_format($matiere->note_min, 2) }}</td>
                                    <td class="text-center">{{ $matiere->reussites }}/{{ $matiere->total }}</td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 15px; width: 100px;">
                                            <div class="progress-bar bg-{{ $pourcentageReussite >= 80 ? 'success' : ($pourcentageReussite >= 60 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $pourcentageReussite }}%">
                                            </div>
                                        </div>
                                        <small>{{ number_format($pourcentageReussite, 1) }}%</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Évolution temporelle -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="action-card">
                <h5 class="mb-3">
                    <i class="fas fa-chart-line me-2"></i>Évolution sur 6 Mois
                </h5>
                <canvas id="evolutionChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistiques professeurs -->
    <div class="row">
        <div class="col-md-12">
            <div class="action-card">
                <h5 class="mb-3">
                    <i class="fas fa-users me-2"></i>Activité des Professeurs
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Professeur</th>
                                <th class="text-center">Matières</th>
                                <th class="text-center">Filières</th>
                                <th class="text-center">Notes Saisies</th>
                                <th class="text-center">Dernière Activité</th>
                                <th class="text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['professeurs'] as $prof)
                                @php
                                    $isActive = $prof['derniere_activite'] && 
                                               \Carbon\Carbon::parse($prof['derniere_activite'])->isAfter(now()->subDays(30));
                                @endphp
                                <tr>
                                    <td><strong>{{ $prof['nom'] }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $prof['matieres_count'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $prof['filieres_count'] }}</span>
                                    </td>
                                    <td class="text-center">{{ $prof['notes_saisies'] }}</td>
                                    <td class="text-center">
                                        @if($prof['derniere_activite'])
                                            {{ \Carbon\Carbon::parse($prof['derniere_activite'])->diffForHumans() }}
                                        @else
                                            <span class="text-muted">Jamais</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $isActive ? 'success' : 'warning' }}">
                                            {{ $isActive ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des mentions
const mentionsCtx = document.getElementById('mentionsChart').getContext('2d');
new Chart(mentionsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Excellent', 'Bien', 'Assez Bien', 'Passable', 'Insuffisant'],
        datasets: [{
            data: [
                {{ $stats['notes']['distribution']['excellent'] }},
                {{ $stats['notes']['distribution']['bien'] }},
                {{ $stats['notes']['distribution']['assez_bien'] }},
                {{ $stats['notes']['distribution']['passable'] }},
                {{ $stats['notes']['distribution']['insuffisant'] }}
            ],
            backgroundColor: ['#28a745', '#007bff', '#ffc107', '#6c757d', '#dc3545'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    fontSize: 10
                }
            }
        }
    }
});

// Graphique d'évolution
const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
new Chart(evolutionCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($stats['evolution'] as $mois)
                '{{ $mois["mois"] }}',
            @endforeach
        ],
        datasets: [{
            label: 'Nouveaux Stagiaires',
            data: [
                @foreach($stats['evolution'] as $mois)
                    {{ $mois['stagiaires'] }},
                @endforeach
            ],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            tension: 0.4
        }, {
            label: 'Notes Saisies',
            data: [
                @foreach($stats['evolution'] as $mois)
                    {{ $mois['notes'] }},
                @endforeach
            ],
            borderColor: '#28a745',
            backgroundColor: 'rgba(40, 167, 69, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});
</script>
@endpush