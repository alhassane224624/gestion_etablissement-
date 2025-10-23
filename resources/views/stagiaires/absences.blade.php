@extends('layouts.app-stagiaire')

@section('title', 'Mes Absences')
@section('page-title', 'Mes Absences')

@section('content')
<div class="container-fluid">
    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-times fa-2x text-warning mb-3"></i>
                    <h6 class="text-muted mb-2">Total Absences</h6>
                    <h2 class="fw-bold mb-0 text-warning">{{ $statistiques['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-times-circle fa-2x text-danger mb-3"></i>
                    <h6 class="text-muted mb-2">Injustifiées</h6>
                    <h2 class="fw-bold mb-0 text-danger">{{ $statistiques['injustifiees'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <h6 class="text-muted mb-2">Justifiées</h6>
                    <h2 class="fw-bold mb-0 text-success">{{ $statistiques['justifiees'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerte si trop d'absences -->
    @if($statistiques['injustifiees'] >= 5)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Attention !</h5>
                        <p class="mb-0">Vous avez <strong>{{ $statistiques['injustifiees'] }} absences injustifiées</strong>. Veuillez régulariser votre situation rapidement.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Filtre période -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-filter text-primary me-2"></i>
                        Filtrer par période
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('stagiaire.absences') }}">
                        <div class="row">
                            <div class="col-md-8">
                                <select name="periode_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Toutes les périodes</option>
                                    @foreach($periodes as $periode)
                                        <option value="{{ $periode->id }}" {{ $periodeId == $periode->id ? 'selected' : '' }}>
                                            {{ $periode->nom }} - {{ $periode->anneeScolaire->nom ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des absences -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-list text-primary me-2"></i>
                        Historique des Absences
                    </h5>
                </div>
                <div class="card-body">
                    @if($absences->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Durée</th>
                                        <th>Statut</th>
                                        <th>Motif</th>
                                        <th>Enregistré par</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($absences as $absence)
                                        <tr class="{{ !$absence->justifiee ? 'table-danger' : '' }}">
                                            <td>
                                                <strong>{{ $absence->date->format('d/m/Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $absence->date->isoFormat('dddd') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $absence->type_libelle }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $absence->duree }}</strong>
                                            </td>
                                            <td>
                                                @if($absence->justifiee)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>Justifiée
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle me-1"></i>Injustifiée
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($absence->motif)
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#motifModal{{ $absence->id }}">
                                                        <i class="fas fa-eye me-1"></i>Voir
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $absence->creator->name ?? 'N/A' }}</small>
                                            </td>
                                        </tr>

                                        <!-- Modal Motif -->
                                        @if($absence->motif)
                                        <div class="modal fade" id="motifModal{{ $absence->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Motif de l'absence</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <strong>Date :</strong> {{ $absence->date->format('d/m/Y') }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>Type :</strong> {{ $absence->type_libelle }}
                                                        </div>
                                                        <div class="mb-3">
                                                            <strong>Motif :</strong>
                                                            <p class="mt-2 p-3 bg-light rounded">{{ $absence->motif }}</p>
                                                        </div>
                                                        @if($absence->document_justificatif)
                                                            <div class="alert alert-info">
                                                                <i class="fas fa-paperclip me-2"></i>
                                                                Document justificatif attaché
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $absences->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                            <h4 class="text-muted mb-3">Aucune absence enregistrée</h4>
                            <p class="text-muted mb-0">
                                @if($periodeId)
                                    Vous n'avez aucune absence pour cette période. Continuez ainsi !
                                @else
                                    Félicitations ! Vous êtes assidu. Continuez ainsi !
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informations importantes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Informations Importantes
                    </h6>
                    <ul class="mb-0 small text-muted">
                        <li class="mb-2">Les absences injustifiées peuvent impacter votre assiduité et vos résultats</li>
                        <li class="mb-2">Pour justifier une absence, contactez l'administration via la messagerie</li>
                        <li class="mb-2">Un justificatif médical est requis pour les absences de plus de 3 jours consécutifs</li>
                        <li class="mb-0">Au-delà de 10 absences injustifiées, votre scolarité peut être remise en question</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection