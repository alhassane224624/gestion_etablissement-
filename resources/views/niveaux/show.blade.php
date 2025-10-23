@extends('layouts.app')

@section('title', 'Détails du Niveau')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-layer-group"></i> {{ $niveau->nom }}
        </h1>
        <div>
            <a href="{{ route('niveaux.edit', $niveau) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('niveaux.index') }}" class="btn btn-secondary">
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
                                Classes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_classes'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users-class fa-2x text-gray-300"></i>
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
                                Stagiaires
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_stagiaires'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
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
                                Matières
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_matieres'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
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
                                Durée
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $niveau->duree_semestres }} Semestre(s)
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations générales -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informations Générales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted">Nom du Niveau</label>
                        <p class="font-weight-bold">{{ $niveau->nom }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Filière</label>
                        <p>
                            <a href="{{ route('filieres.show', $niveau->filiere) }}" class="text-decoration-none">
                                <span class="badge badge-info badge-lg">
                                    {{ $niveau->filiere->nom }}
                                </span>
                            </a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Ordre dans la Filière</label>
                        <p>
                            <span class="badge badge-primary badge-lg">
                                Position: {{ $niveau->ordre }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Durée</label>
                        <p>
                            <i class="fas fa-calendar"></i> {{ $niveau->duree_semestres }} semestre(s)
                            <small class="text-muted d-block">
                                (≈ {{ $niveau->duree_semestres / 2 }} année(s))
                            </small>
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="small text-muted">Nom Complet</label>
                        <p class="font-weight-bold text-primary">{{ $niveau->nom_complet }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <!-- Onglets -->
            <ul class="nav nav-tabs" id="niveauTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="matieres-tab" data-toggle="tab" href="#matieres" role="tab">
                        <i class="fas fa-book"></i> Matières ({{ $niveau->matieres->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="classes-tab" data-toggle="tab" href="#classes" role="tab">
                        <i class="fas fa-users-class"></i> Classes ({{ $niveau->classes->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="stagiaires-tab" data-toggle="tab" href="#stagiaires" role="tab">
                        <i class="fas fa-users"></i> Stagiaires ({{ $niveau->stagiaires->count() }})
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="niveauTabContent">
                <!-- Onglet Matières -->
                <div class="tab-pane fade show active" id="matieres" role="tabpanel">
                    <div class="card shadow">
                        <div class="card-body">
                            @if($niveau->matieres->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Matière</th>
                                                <th>Code</th>
                                                <th>Coefficient</th>
                                                <th>Heures</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($niveau->matieres as $matiere)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $matiere->nom }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-secondary">
                                                            {{ $matiere->code }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            Coef: {{ $matiere->coefficient }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $matiere->pivot->heures_cours ?? 'N/A' }}h
                                                    </td>
                                                    <td>
                                                        @if($matiere->pivot->is_obligatoire)
                                                            <span class="badge badge-danger">Obligatoire</span>
                                                        @else
                                                            <span class="badge badge-warning">Optionnelle</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-book fa-3x mb-3"></i>
                                    <p>Aucune matière associée à ce niveau</p>
                                    <a href="{{ route('niveaux.edit', $niveau) }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Ajouter des Matières
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Onglet Classes -->
                <div class="tab-pane fade" id="classes" role="tabpanel">
                    <div class="card shadow">
                        <div class="card-body">
                            @if($niveau->classes->count() > 0)
                                <div class="row">
                                    @foreach($niveau->classes as $classe)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-left-primary">
                                                <div class="card-body">
                                                    <h6 class="font-weight-bold">
                                                        <a href="{{ route('classes.show', $classe) }}">
                                                            {{ $classe->nom }}
                                                        </a>
                                                    </h6>
                                                    <p class="mb-2 small">
                                                        <i class="fas fa-users"></i> 
                                                        Effectif: {{ $classe->effectif_actuel }} / {{ $classe->effectif_max }}
                                                    </p>
                                                    <div class="progress" style="height: 15px;">
                                                        <div class="progress-bar" 
                                                             style="width: {{ $classe->taux_remplissage }}%">
                                                            {{ number_format($classe->taux_remplissage, 1) }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-users-class fa-3x mb-3"></i>
                                    <p>Aucune classe pour ce niveau</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Onglet Stagiaires -->
                <div class="tab-pane fade" id="stagiaires" role="tabpanel">
                    <div class="card shadow">
                        <div class="card-body">
                            @if($niveau->stagiaires->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Matricule</th>
                                                <th>Nom & Prénom</th>
                                                <th>Classe</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($niveau->stagiaires->take(10) as $stagiaire)
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-secondary">
                                                            {{ $stagiaire->matricule }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $stagiaire->nom }} {{ $stagiaire->prenom }}</td>
                                                    <td>{{ $stagiaire->classe->nom ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($stagiaire->is_active)
                                                            <span class="badge badge-success">Actif</span>
                                                        @else
                                                            <span class="badge badge-secondary">Inactif</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('stagiaires.show', $stagiaire) }}" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($niveau->stagiaires->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('stagiaires.index', ['niveau_id' => $niveau->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            Voir tous les stagiaires ({{ $niveau->stagiaires->count() }})
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>Aucun stagiaire inscrit dans ce niveau</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection