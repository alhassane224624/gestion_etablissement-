@extends('layouts.app-professeur')

@section('title', 'Gestion des Présences')
@section('page-title', 'Marquer les Présences')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Gestion des Présences
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('professeur.presences') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Classe</label>
                                <select name="classe_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Sélectionner une classe --</option>
                                    @foreach($classes as $classe)
                                        <option value="{{ $classe->id }}" {{ $classeId == $classe->id ? 'selected' : '' }}>
                                            {{ $classe->nom }} - {{ $classe->filiere->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($classeId && $stagiaires->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">
                            Liste des Stagiaires - {{ \Carbon\Carbon::parse($date)->isoFormat('dddd D MMMM YYYY') }}
                        </h6>
                        <span class="badge bg-info">
                            {{ $stagiaires->count() }} stagiaires
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Matricule</th>
                                    <th>Nom Complet</th>
                                    <th>Filière</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stagiaires as $stagiaire)
                                    @php
                                        $absence = $absences->get($stagiaire->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $stagiaire->matricule }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <strong>{{ substr($stagiaire->nom, 0, 1) }}{{ substr($stagiaire->prenom, 0, 1) }}</strong>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $stagiaire->nom }} {{ $stagiaire->prenom }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $stagiaire->filiere->nom ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            @if($absence)
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i> Absent(e)
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Présent(e)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($absence)
                                                <form action="{{ route('professeur.presences.supprimer', $absence->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Marquer ce stagiaire comme présent ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-success" title="Marquer présent">
                                                        <i class="fas fa-check"></i> Présent
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-info" title="Voir détails" data-bs-toggle="modal" data-bs-target="#detailAbsence{{ $stagiaire->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalAbsence{{ $stagiaire->id }}">
                                                    <i class="fas fa-times"></i> Absent
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal Marquer Absence -->
                                    <div class="modal fade" id="modalAbsence{{ $stagiaire->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('professeur.presences.marquer') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="stagiaire_id" value="{{ $stagiaire->id }}">
                                                    <input type="hidden" name="date" value="{{ $date }}">
                                                    
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Marquer Absence - {{ $stagiaire->nom }} {{ $stagiaire->prenom }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Type d'absence</label>
                                                            <select name="type" class="form-select" required>
                                                                <option value="matin">Matin</option>
                                                                <option value="apres_midi">Après-midi</option>
                                                                <option value="journee">Journée complète</option>
                                                                <option value="heure">Par heure</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Motif (optionnel)</label>
                                                            <textarea name="motif" class="form-control" rows="3" placeholder="Raison de l'absence..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-save me-1"></i> Enregistrer
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Détails Absence -->
                                    @if($absence)
                                    <div class="modal fade" id="detailAbsence{{ $stagiaire->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Détails de l'absence</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-sm">
                                                        <tr>
                                                            <th>Type:</th>
                                                            <td>{{ $absence->type_libelle }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Date:</th>
                                                            <td>{{ $absence->date->format('d/m/Y') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Durée:</th>
                                                            <td>{{ $absence->duree }}</td>
                                                        </tr>
                                                        @if($absence->motif)
                                                        <tr>
                                                            <th>Motif:</th>
                                                            <td>{{ $absence->motif }}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Justifiée:</th>
                                                            <td>
                                                                <span class="badge bg-{{ $absence->justifiee ? 'success' : 'danger' }}">
                                                                    {{ $absence->justifiee ? 'Oui' : 'Non' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($classeId)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Aucun stagiaire trouvé dans cette classe.
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-clipboard-check fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Sélectionnez une classe pour commencer</h5>
                    <p class="text-muted mb-0">Choisissez une date et une classe pour gérer les présences</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection