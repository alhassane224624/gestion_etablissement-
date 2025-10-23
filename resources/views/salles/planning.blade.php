@extends('layouts.app')

@section('title', 'Planning - ' . $salle->nom)

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">{{ $salle->nom }}</h2>
                            <p class="text-muted mb-0">
                                <i class="fas fa-users me-2"></i>Capacité: {{ $salle->capacite }} personnes
                                <span class="mx-2">|</span>
                                <i class="fas fa-door-open me-2"></i>Type: {{ ucfirst($salle->type) }}
                                <span class="mx-2">|</span>
                                <span class="badge bg-{{ $salle->disponible ? 'success' : 'danger' }}">
                                    {{ $salle->disponible ? 'Disponible' : 'Indisponible' }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('salles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation semaine -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('salles.planning', ['salle' => $salle->id, 'semaine' => $debutSemaine->copy()->subWeek()->format('o-W')]) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-chevron-left me-2"></i>Semaine précédente
                        </a>
                        
                        <div class="text-center">
                            <h4 class="mb-1">Semaine {{ $debutSemaine->format('W') }}</h4>
                            <p class="text-muted mb-0">
                                {{ $debutSemaine->format('d/m/Y') }} - {{ $finSemaine->format('d/m/Y') }}
                            </p>
                        </div>
                        
                        <a href="{{ route('salles.planning', ['salle' => $salle->id, 'semaine' => $debutSemaine->copy()->addWeek()->format('o-W')]) }}" 
                           class="btn btn-outline-primary">
                            Semaine suivante<i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                    
                    <!-- Bouton semaine actuelle -->
                    <div class="text-center mt-3">
                        <a href="{{ route('salles.planning', ['salle' => $salle->id, 'semaine' => now()->format('o-W')]) }}" 
                           class="btn btn-sm btn-info">
                            <i class="fas fa-calendar-day me-2"></i>Semaine actuelle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Planning hebdomadaire -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="120">Heure</th>
                                    @foreach($planning_semaine as $jour => $data)
                                        <th class="text-center">
                                            <div>{{ $jour }}</div>
                                            <small class="text-muted">{{ $data['date']->format('d/m') }}</small>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $heures = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
                                @endphp
                                
                                @foreach($heures as $heure)
                                    <tr>
                                        <td class="text-center fw-bold bg-light">{{ $heure }}</td>
                                        @foreach($planning_semaine as $jour => $data)
                                            <td class="p-1">
                                                @php
                                                    $coursHeure = $data['cours']->filter(function($cours) use ($heure) {
                                                        $heureDebut = substr($cours->heure_debut, 0, 5);
                                                        $heureFin = substr($cours->heure_fin, 0, 5);
                                                        return $heureDebut <= $heure && $heureFin > $heure;
                                                    });
                                                @endphp
                                                
                                                @foreach($coursHeure as $cours)
                                                    @php
                                                        $colors = [
                                                            'cours' => 'primary',
                                                            'td' => 'success',
                                                            'tp' => 'warning',
                                                            'examen' => 'danger'
                                                        ];
                                                        $color = $colors[$cours->type_cours] ?? 'secondary';
                                                        
                                                        $statusColors = [
                                                            'brouillon' => 'secondary',
                                                            'valide' => 'success',
                                                            'en_cours' => 'info',
                                                            'termine' => 'dark',
                                                            'annule' => 'danger'
                                                        ];
                                                        $statusColor = $statusColors[$cours->statut] ?? 'secondary';
                                                    @endphp
                                                    
                                                    <div class="card mb-1 border-{{ $color }}" 
                                                         style="cursor: pointer;"
                                                         data-bs-toggle="modal" 
                                                         data-bs-target="#coursModal{{ $cours->id }}">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                                <small class="badge bg-{{ $color }} text-white">
                                                                    {{ strtoupper($cours->type_cours) }}
                                                                </small>
                                                                <small class="badge bg-{{ $statusColor }}">
                                                                    {{ ucfirst($cours->statut) }}
                                                                </small>
                                                            </div>
                                                            <div class="fw-bold small text-truncate" title="{{ $cours->matiere->nom }}">
                                                                {{ $cours->matiere->nom }}
                                                            </div>
                                                            <small class="text-muted d-block">
                                                                {{ substr($cours->heure_debut, 0, 5) }} - {{ substr($cours->heure_fin, 0, 5) }}
                                                            </small>
                                                            <small class="text-muted d-block text-truncate">
                                                                <i class="fas fa-user-tie me-1"></i>{{ $cours->professeur->name }}
                                                            </small>
                                                            <small class="text-muted d-block text-truncate">
                                                                <i class="fas fa-graduation-cap me-1"></i>
                                                                {{ $cours->classe->niveau->nom ?? '' }} {{ $cours->classe->filiere->nom ?? '' }}
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <!-- Modal détails du cours -->
                                                    <div class="modal fade" id="coursModal{{ $cours->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-{{ $color }} text-white">
                                                                    <h5 class="modal-title">
                                                                        <i class="fas fa-book me-2"></i>{{ $cours->matiere->nom }}
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-6 mb-3">
                                                                            <strong><i class="fas fa-clock me-2"></i>Horaire:</strong>
                                                                            <p class="mb-0">{{ substr($cours->heure_debut, 0, 5) }} - {{ substr($cours->heure_fin, 0, 5) }}</p>
                                                                        </div>
                                                                        <div class="col-6 mb-3">
                                                                            <strong><i class="fas fa-calendar me-2"></i>Date:</strong>
                                                                            <p class="mb-0">{{ $cours->date->format('d/m/Y') }}</p>
                                                                        </div>
                                                                        <div class="col-6 mb-3">
                                                                            <strong><i class="fas fa-chalkboard-teacher me-2"></i>Type:</strong>
                                                                            <p class="mb-0">
                                                                                <span class="badge bg-{{ $color }}">{{ strtoupper($cours->type_cours) }}</span>
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-6 mb-3">
                                                                            <strong><i class="fas fa-info-circle me-2"></i>Statut:</strong>
                                                                            <p class="mb-0">
                                                                                <span class="badge bg-{{ $statusColor }}">{{ ucfirst($cours->statut) }}</span>
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <strong><i class="fas fa-user-tie me-2"></i>Professeur:</strong>
                                                                            <p class="mb-0">{{ $cours->professeur->name }}</p>
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <strong><i class="fas fa-graduation-cap me-2"></i>Classe:</strong>
                                                                            <p class="mb-0">
                                                                                {{ $cours->classe->niveau->nom ?? '' }} {{ $cours->classe->filiere->nom ?? '' }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-12 mb-3">
                                                                            <strong><i class="fas fa-door-open me-2"></i>Salle:</strong>
                                                                            <p class="mb-0">{{ $cours->salle->nom }}</p>
                                                                        </div>
                                                                        @if($cours->description)
                                                                            <div class="col-12 mb-3">
                                                                                <strong><i class="fas fa-sticky-note me-2"></i>Description:</strong>
                                                                                <p class="mb-0">{{ $cours->description }}</p>
                                                                            </div>
                                                                        @endif
                                                                        @if($cours->statut === 'annule' && $cours->motif_annulation)
                                                                            <div class="col-12">
                                                                                <div class="alert alert-danger mb-0">
                                                                                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Motif d'annulation:</strong>
                                                                                    <p class="mb-0 mt-1">{{ $cours->motif_annulation }}</p>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    @can('update', $cours)
                                                                        <a href="{{ route('planning.edit', $cours) }}" class="btn btn-primary">
                                                                            <i class="fas fa-edit me-2"></i>Modifier
                                                                        </a>
                                                                    @endcan
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Légende -->
                    <div class="mt-4">
                        <h6>Légende:</h6>
                        <div class="d-flex flex-wrap gap-3">
                            <span><span class="badge bg-primary">COURS</span> Cours magistral</span>
                            <span><span class="badge bg-success">TD</span> Travaux dirigés</span>
                            <span><span class="badge bg-warning text-dark">TP</span> Travaux pratiques</span>
                            <span><span class="badge bg-danger">EXAMEN</span> Examen</span>
                        </div>
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            <span><span class="badge bg-secondary">Brouillon</span></span>
                            <span><span class="badge bg-success">Validé</span></span>
                            <span><span class="badge bg-info">En cours</span></span>
                            <span><span class="badge bg-dark">Terminé</span></span>
                            <span><span class="badge bg-danger">Annulé</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table td {
        vertical-align: top;
        min-height: 80px;
    }
    
    .card-body.p-2 {
        font-size: 0.85rem;
    }
    
    .modal-header.bg-primary,
    .modal-header.bg-success,
    .modal-header.bg-warning,
    .modal-header.bg-danger {
        color: white !important;
    }
    
    .table-bordered td {
        position: relative;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transition: box-shadow 0.3s ease-in-out;
    }
</style>
@endpush
@endsection