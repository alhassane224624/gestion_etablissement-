@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-door-open"></i> Détails de la Salle</h2>
                <div>
                    <a href="{{ route('salles.planning', $salle) }}" class="btn btn-info">
                        <i class="fas fa-calendar"></i> Planning
                    </a>
                    <a href="{{ route('salles.edit', $salle) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <a href="{{ route('salles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom:</strong> {{ $salle->nom }}</p>
                    <p><strong>Type:</strong> {{ $salle->type_libelle }}</p>
                    <p><strong>Capacité:</strong> <i class="fas fa-users"></i> {{ $salle->capacite }} places</p>
                    <p><strong>Bâtiment:</strong> {{ $salle->batiment ?? 'Non spécifié' }}</p>
                    <p><strong>Étage:</strong> {{ $salle->etage ?? 'Non spécifié' }}</p>
                    <p>
                        <strong>Statut:</strong>
                        @if($salle->disponible)
                            <span class="badge badge-success">Disponible</span>
                        @else
                            <span class="badge badge-danger">Indisponible</span>
                        @endif
                    </p>

                    @if($salle->description)
                        <hr>
                        <p><strong>Description:</strong></p>
                        <p class="text-muted">{{ $salle->description }}</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-tools"></i> Équipements</h5>
                </div>
                <div class="card-body">
                    @if($salle->equipements && count($salle->equipements_list) > 0)
                        <ul class="list-unstyled mb-0">
                            @foreach($salle->equipements_list as $equipement)
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success"></i> {{ $equipement }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Aucun équipement renseigné</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Statistiques -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Cours cette semaine</h6>
                            <h3>{{ $stats['cours_cette_semaine'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Heures utilisées</h6>
                            <h3>{{ $stats['heures_utilisees_semaine'] }}h</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Taux d'occupation</h6>
                            <h3>{{ $stats['taux_occupation'] }}%</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planning de la semaine -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-week"></i> Planning de la semaine en cours</h5>
                </div>
                <div class="card-body">
                    @if($plannings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Horaire</th>
                                        <th>Matière</th>
                                        <th>Classe</th>
                                        <th>Professeur</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($plannings as $planning)
                                        <tr>
                                            <td>{{ $planning->date->format('d/m/Y') }}</td>
                                            <td>{{ $planning->heure_debut }} - {{ $planning->heure_fin }}</td>
                                            <td>{{ $planning->matiere->nom }}</td>
                                            <td>{{ $planning->classe->nom }}</td>
                                            <td>{{ $planning->professeur->name }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $planning->type_cours_libelle }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucun cours planifié cette semaine</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('salles.planning', $salle) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-calendar"></i> Voir tout le planning
                    </a>
                </div>
            </div>

            @if($stats['professeur_principal'])
                <div class="card mt-3">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="fas fa-user-tie"></i> Professeur principal</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            <strong>{{ $stats['professeur_principal']->name }}</strong> utilise le plus cette salle
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection