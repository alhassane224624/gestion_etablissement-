@extends('layouts.app')

@section('title', 'Détails de la Filière')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-graduation-cap"></i> {{ $filiere->nom }}
        </h1>
        <div>
            <a href="{{ route('filieres.edit', $filiere) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('filieres.index') }}" class="btn btn-secondary">
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
                                Stagiaires Actifs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $filiere->getTotalStagiairesActifs() }}
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Matières
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $filiere->matieres->count() }}
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Professeurs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $filiere->professeurs->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
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
                                Niveaux
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $filiere->niveaux->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations générales -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informations Générales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted">Nom de la Filière</label>
                        <p class="font-weight-bold">{{ $filiere->nom }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Niveau</label>
                        <p>
                            <span class="badge badge-info badge-lg">
                                {{ $filiere->niveau }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="small text-muted">Nom Complet</label>
                        <p class="font-weight-bold text-primary">{{ $filiere->nom_complet }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <!-- Onglets -->
            <ul class="nav nav-tabs" id="filiereTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="niveaux-tab" data-toggle="tab" href="#niveaux" role="tab">
                        <i class="fas fa-layer-group"></i> Niveaux ({{ $filiere->niveaux->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="matieres-tab" data-toggle="tab" href="#matieres" role="tab">
                        <i class="fas fa-book"></i> Matières ({{ $filiere->matieres->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="professeurs-tab" data-toggle="tab" href="#professeurs" role="tab">
                        <i class="fas fa-chalkboard-teacher"></i> Professeurs ({{ $filiere->professeurs->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="stagiaires-tab" data-toggle="tab" href="#stagiaires" role="tab">
                        <i class="fas fa-users"></i> Stagiaires ({{ $filiere->getTotalStagiairesActifs() }})
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="filiereTabContent">
                <!-- Onglet Niveaux -->
                <div class="tab-pane fade show active" id="niveaux" role="tabpanel">
                    <div class="card shadow">
                        <div class="card-body">
                            @if($filiere->niveaux->count() > 0)
                                <div class="list-group">
                                    @foreach($filiere->niveaux as $niveau)
                                        <a href="{{ route('niveaux.show', $niveau) }}" 
                                           class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $niveau->nom }}</h6>
                                                <small>
                                                    <span class="badge badge-primary">Ordre: {{ $niveau->ordre }}</span>
                                                </small>
                                            </div>
                                            <p class="mb-1 small">
                                                <i class="fas fa-calendar"></i> {{ $niveau->duree_semestres }} semestre(s)
                                            </p>
                                            <small class="text-muted">
                                                {{ $niveau->matieres->count() }} matière(s) - 
                                                {{ $niveau->classes->count() }} classe(s)
                                            </small>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-layer-group fa-3x mb-3"></i>
                                    <p>Aucun niveau dans cette filière</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Onglet Matières -->
                <div class="tab-pane fade" id="matieres" role="tabpanel">
                    <div class="card shadow">
                        <div class="card-body">
                            @if($filiere->matieres->count() > 0)
                                <div class="row">
                                    @foreach($filiere->matieres as $matiere)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-left-success">
                                                <div class="card-body">
                                                    <h6 class="font-weight-bold">
                                                        {{ $matiere->nom }}
                                                    </h6>
                                                    <p class="mb-2 small">
                                                        <span class="badge badge-secondary">{{ $matiere->code }}</span>
                                                        <span class="badge badge-info">Coef: {{ $matiere->coefficient }}</span>
                                                    </p>
                                                    <a href="{{ route('matieres.show', $matiere) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        Voir détails
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-book fa-3x mb-3"></i>
                                    <p>Aucune matière associée</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Onglet Professeurs -->
                <div class="tab-pane fade" id="professeurs" role="tabpanel">
                    <div class="card shadow">
                        <div class="card-body">
                            @if($filiere->professeurs->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Spécialité</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($filiere->professeurs as $professeur)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $professeur->name }}</strong>
                                                    </td>
                                                    <td>{{ $professeur->email }}</td>
                                                    <td>
                                                        <small>{{ $professeur->specialite ?? 'N/A' }}</small>
                                                    </td>
                                                    <td>
                                                        @if($professeur->pivot->is_active)
                                                            <span class="badge badge-success">Actif</span>
                                                        @else
                                                            <span class="badge badge-secondary">Inactif</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                                    <p>Aucun professeur assigné</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Onglet Stagiaires -->
                <div class="tab-pane fade" id="stagiaires" role="tabpanel">
                    <div class="card shadow">
                        <div class="card-body">
                            @if($filiere->stagiaires->count() > 0)
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
                                            @foreach($filiere->stagiaires->take(10) as $stagiaire)
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
                                @if($filiere->stagiaires->count() > 10)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('stagiaires.index', ['filiere_id' => $filiere->id]) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            Voir tous les stagiaires ({{ $filiere->stagiaires->count() }})
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>Aucun stagiaire inscrit</p>
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