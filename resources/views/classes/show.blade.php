@extends('layouts.app')

@section('title', 'Détails de la Classe')

@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users-class"></i> {{ $classe->nom }}
        </h1>
        <div>
            <a href="{{ route('classes.edit', $classe) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Effectif Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_stagiaires'] }} / {{ $classe->effectif_max }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Places Restantes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['places_restantes'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chair fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Taux de Remplissage
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ number_format($stats['taux_remplissage'], 1) }}%
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                             style="width: {{ $stats['taux_remplissage'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Statut
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($classe->is_full)
                                    <span class="badge badge-danger">Complète</span>
                                @else
                                    <span class="badge badge-success">Ouverte</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations de la classe -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informations Générales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted">Nom de la Classe</label>
                        <p class="font-weight-bold">{{ $classe->nom }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Filière</label>
                        <p>
                            <span class="badge badge-info">
                                {{ $classe->filiere->nom }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Niveau</label>
                        <p>
                            <span class="badge badge-secondary">
                                {{ $classe->niveau->nom }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Année Scolaire</label>
                        <p>
                            {{ $classe->anneeScolaire->nom }}
                            @if($classe->anneeScolaire->is_active)
                                <span class="badge badge-success">Active</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Période</label>
                        <p>
                            Du {{ $classe->anneeScolaire->debut->format('d/m/Y') }}
                            au {{ $classe->anneeScolaire->fin->format('d/m/Y') }}
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="small text-muted">Nom Complet</label>
                        <p class="font-weight-bold text-primary">{{ $classe->nom_complet }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des stagiaires -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users"></i> Liste des Stagiaires ({{ $classe->stagiaires->count() }})
                    </h6>
                    @if(!$classe->is_full)
                        <a href="{{ route('stagiaires.create', ['classe_id' => $classe->id]) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un Stagiaire
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($classe->stagiaires->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Matricule</th>
                                        <th>Nom & Prénom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classe->stagiaires as $stagiaire)
                                        <tr>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ $stagiaire->matricule }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $stagiaire->nom }} {{ $stagiaire->prenom }}</strong>
                                            </td>
                                            <td>
                                                <small>{{ $stagiaire->email ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $stagiaire->telephone ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                @if($stagiaire->is_active && $stagiaire->statut === 'actif')
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $stagiaire->statut_libelle }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('stagiaires.show', $stagiaire) }}" 
                                                   class="btn btn-sm btn-info"
                                                   title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>Aucun stagiaire dans cette classe</p>
                            <a href="{{ route('stagiaires.create', ['classe_id' => $classe->id]) }}" 
                               class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Ajouter le Premier Stagiaire
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection