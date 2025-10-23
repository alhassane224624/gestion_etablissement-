@extends('layouts.app')

@section('title', 'Modifier la Filière')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit"></i> Modifier la Filière: {{ $filiere->nom }}
        </h1>
        <a href="{{ route('filieres.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle"></i> Erreurs de validation:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informations de la Filière
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('filieres.update', $filiere) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nom">Nom de la filière <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', $filiere->nom) }}"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="niveau">Niveau <span class="text-danger">*</span></label>
                            <select class="form-control @error('niveau') is-invalid @enderror" 
                                    id="niveau" 
                                    name="niveau"
                                    required>
                                <option value="">Sélectionner un niveau</option>
                                <option value="Technicien" {{ old('niveau', $filiere->niveau) == 'Technicien' ? 'selected' : '' }}>Technicien</option>
                                <option value="Technicien Spécialisé" {{ old('niveau', $filiere->niveau) == 'Technicien Spécialisé' ? 'selected' : '' }}>Technicien Spécialisé</option>
                                <option value="Qualification" {{ old('niveau', $filiere->niveau) == 'Qualification' ? 'selected' : '' }}>Qualification</option>
                                <option value="BTS" {{ old('niveau', $filiere->niveau) == 'BTS' ? 'selected' : '' }}>BTS</option>
                                <option value="Licence" {{ old('niveau', $filiere->niveau) == 'Licence' ? 'selected' : '' }}>Licence</option>
                                <option value="Master" {{ old('niveau', $filiere->niveau) == 'Master' ? 'selected' : '' }}>Master</option>
                            </select>
                            @error('niveau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('filieres.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer les Modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-chart-pie"></i> Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted">Stagiaires Actifs</label>
                        <h5 class="text-primary">{{ $filiere->getTotalStagiairesActifs() }}</h5>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Matières Associées</label>
                        <h5 class="text-info">{{ $filiere->matieres->count() }}</h5>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted">Professeurs</label>
                        <h5 class="text-success">{{ $filiere->professeurs->count() }}</h5>
                    </div>

                    <div class="mb-0">
                        <label class="small text-muted">Niveaux</label>
                        <h5 class="text-warning">{{ $filiere->niveaux->count() }}</h5>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4 border-left-warning">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Attention
                    </h6>
                    <p class="small mb-0">
                        La modification de la filière peut impacter les stagiaires, classes et niveaux associés.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection