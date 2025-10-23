@extends('layouts.app')

@section('title', 'Gestion des Périodes')
@section('page-title', 'Années et Périodes Scolaires')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="action-card">
                <h5 class="mb-3">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    Organisation Scolaire
                </h5>
                <p class="text-muted">
                    Gérez les années scolaires et leurs périodes (semestres, trimestres).
                    Une seule période peut être active à la fois pour la saisie des notes.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="action-card text-center">
                <h6 class="text-muted mb-2">Actions</h6>
                <div class="d-grid gap-2">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nouvelleAnneeModal">
                        <i class="fas fa-plus me-2"></i>Nouvelle Année
                    </button>
                    <a href="{{ route('periodes.create') }}" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Nouvelle Période
                    </a>
                </div>
            </div>
        </div>
    </div>

    @foreach($annees as $annee)
        <div class="action-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    Année {{ $annee->nom }}
                    @if($annee->is_active)
                        <span class="badge bg-success ms-2">ACTIVE</span>
                    @endif
                </h5>
                <div class="text-muted">
                    <small>{{ $annee->debut->format('d/m/Y') }} au {{ $annee->fin->format('d/m/Y') }}</small>
                </div>
            </div>

            @if($annee->periodes->count() > 0)
                <div class="row">
                    @foreach($annee->periodes as $periode)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 {{ $periode->is_active ? 'border-success' : 'border-secondary' }}">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <strong>{{ $periode->nom }}</strong>
                                    @if($periode->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Type :</small>
                                        <span class="badge bg-info">{{ ucfirst($periode->type) }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Période :</small><br>
                                        <strong>{{ $periode->debut->format('d/m/Y') }}</strong> au 
                                        <strong>{{ $periode->fin->format('d/m/Y') }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Durée :</small>
                                        {{ $periode->debut->diffInDays($periode->fin) }} jours
                                    </div>
                                    
                                    @php
                                        $aujourd_hui = now();
                                        $statut = '';
                                        $statut_class = '';
                                        if ($aujourd_hui->lt($periode->debut)) {
                                            $statut = 'À venir';
                                            $statut_class = 'text-info';
                                        } elseif ($aujourd_hui->between($periode->debut, $periode->fin)) {
                                            $statut = 'En cours';
                                            $statut_class = 'text-success';
                                        } else {
                                            $statut = 'Terminée';
                                            $statut_class = 'text-muted';
                                        }
                                    @endphp
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Statut :</small>
                                        <span class="{{ $statut_class }} fw-bold">{{ $statut }}</span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group w-100">
                                        @if(!$periode->is_active && $annee->is_active)
                                            <form method="POST" action="{{ route('periodes.activer', $periode) }}" class="flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm w-100" 
                                                        onclick="return confirm('Activer cette période ? Cela désactivera la période actuellement active.')">
                                                    <i class="fas fa-play me-1"></i>Activer
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button class="btn btn-info btn-sm" onclick="voirDetailsPeriode({{ $periode->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="modifierPeriode({{ $periode->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucune période définie pour cette année scolaire.
                    <a href="{{ route('periodes.create', ['annee_id' => $annee->id]) }}" class="alert-link">
                        Créer une période
                    </a>
                </div>
            @endif

            <!-- Statistiques de l'année -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="text-center p-2 bg-light rounded">
                        <small class="text-muted">Périodes</small>
                        <h6 class="mb-0">{{ $annee->periodes->count() }}</h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-2 bg-light rounded">
                        <small class="text-muted">Notes saisies</small>
                        <h6 class="mb-0">{{ $annee->periodes->sum(function($p) { return $p->notes->count() ?? 0; }) }}</h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-2 bg-light rounded">
                        <small class="text-muted">Durée totale</small>
                        <h6 class="mb-0">{{ $annee->debut->diffInDays($annee->fin) }} j</h6>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-2 bg-light rounded">
                        <small class="text-muted">Progression</small>
                        @php
                            $total_jours = $annee->debut->diffInDays($annee->fin);
                            $jours_ecoules = max(0, min($total_jours, $annee->debut->diffInDays(now())));
                            $progression = $total_jours > 0 ? round(($jours_ecoules / $total_jours) * 100) : 0;
                        @endphp
                        <h6 class="mb-0">{{ $progression }}%</h6>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if($annees->isEmpty())
        <div class="action-card text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4>Aucune année scolaire configurée</h4>
            <p class="text-muted">Commencez par créer votre première année scolaire.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nouvelleAnneeModal">
                <i class="fas fa-plus me-2"></i>Créer la première année
            </button>
        </div>
    @endif

    <!-- Modal Nouvelle Année -->
    <div class="modal fade" id="nouvelleAnneeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-plus me-2"></i>Nouvelle Année Scolaire
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/annees-scolaires">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nom de l'année *</label>
                            <input type="text" name="nom" class="form-control" 
                                   placeholder="Ex: 2024-2025" value="{{ date('Y') }}-{{ date('Y') + 1 }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date de début *</label>
                                <input type="date" name="debut" class="form-control" 
                                       value="{{ date('Y-09-01') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date de fin *</label>
                                <input type="date" name="fin" class="form-control" 
                                       value="{{ date('Y') + 1 }}-06-30" required>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive">
                                <label class="form-check-label" for="isActive">
                                    Activer cette année immédiatement
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Créer l'année
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Détails Période -->
    <div class="modal fade" id="detailsPeriodeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails de la Période</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailsPeriodeContent">
                    <!-- Contenu chargé dynamiquement -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function voirDetailsPeriode(periodeId) {
    // Charger les détails de la période
    document.getElementById('detailsPeriodeContent').innerHTML = `
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Chargement...</p>
        </div>
    `;
    
    new bootstrap.Modal(document.getElementById('detailsPeriodeModal')).show();
    
    // Simuler le chargement des données
    setTimeout(() => {
        document.getElementById('detailsPeriodeContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Informations générales</h6>
                    <ul class="list-unstyled">
                        <li><strong>Type:</strong> Semestre</li>
                        <li><strong>Durée:</strong> 120 jours</li>
                        <li><strong>Statut:</strong> <span class="badge bg-success">Active</span></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Statistiques</h6>
                    <ul class="list-unstyled">
                        <li><strong>Notes saisies:</strong> 156</li>
                        <li><strong>Bulletins générés:</strong> 23</li>
                        <li><strong>Moyenne générale:</strong> 14.2/20</li>
                    </ul>
                </div>
            </div>
        `;
    }, 1000);
}

function modifierPeriode(periodeId) {
    // Rediriger vers la page d'édition
    window.location.href = `/periodes/${periodeId}/edit`;
}
</script>
@endpush