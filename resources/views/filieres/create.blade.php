@extends('layouts.app')

@section('title', 'Créer une Filière')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle"></i> Créer une Filière
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
                    <form action="{{ route('filieres.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="nom">Nom de la filière <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}"
                                   placeholder="Ex: Développement Informatique"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Nom complet de la filière
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="niveau">Niveau <span class="text-danger">*</span></label>
                            <select class="form-control @error('niveau') is-invalid @enderror" 
                                    id="niveau" 
                                    name="niveau"
                                    required>
                                <option value="">Sélectionner un niveau</option>
                                <option value="Technicien" {{ old('niveau') == 'Technicien' ? 'selected' : '' }}>Technicien</option>
                                <option value="Technicien Spécialisé" {{ old('niveau') == 'Technicien Spécialisé' ? 'selected' : '' }}>Technicien Spécialisé</option>
                                <option value="Qualification" {{ old('niveau') == 'Qualification' ? 'selected' : '' }}>Qualification</option>
                                <option value="BTS" {{ old('niveau') == 'BTS' ? 'selected' : '' }}>BTS</option>
                                <option value="Licence" {{ old('niveau') == 'Licence' ? 'selected' : '' }}>Licence</option>
                                <option value="Master" {{ old('niveau') == 'Master' ? 'selected' : '' }}>Master</option>
                            </select>
                            @error('niveau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                            <small class="form-text text-muted">
                                Niveau de certification de la filière
                            </small>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('filieres.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer la Filière
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
                        <i class="fas fa-info-circle"></i> Informations
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">À propos des filières:</h6>
                    <p class="small text-muted mb-3">
                        Une filière regroupe un ensemble de formations du même domaine professionnel.
                    </p>

                    <h6 class="font-weight-bold">Niveaux disponibles:</h6>
                    <ul class="small mb-3">
                        <li><strong>Qualification:</strong> Formation de base</li>
                        <li><strong>Technicien:</strong> 2 ans après le bac</li>
                        <li><strong>Technicien Spécialisé:</strong> 2 ans post-qualification</li>
                        <li><strong>BTS:</strong> Brevet de Technicien Supérieur</li>
                        <li><strong>Licence:</strong> 3 ans post-bac</li>
                        <li><strong>Master:</strong> 5 ans post-bac</li>
                    </ul>

                    <h6 class="font-weight-bold">Après création:</h6>
                    <p class="small text-muted mb-0">
                        Vous pourrez ajouter des niveaux, des matières et des professeurs à cette filière.
                    </p>
                </div>
            </div>

            <div class="card shadow mb-4 border-left-warning">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Important
                    </h6>
                    <p class="small mb-0">
                        Le nom de la filière doit être unique dans le système.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection