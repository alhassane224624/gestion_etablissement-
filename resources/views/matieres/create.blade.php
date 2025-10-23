@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-book"></i> Créer une Matière</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('matieres.index') }}">Matières</a></li>
                    <li class="breadcrumb-item active">Créer</li>
                </ol>
            </nav>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('matieres.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations de base</h5>

                        <div class="form-group">
                            <label for="nom">Nom de la matière <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
                            @error('nom')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="code">Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                            <small class="form-text text-muted">Ex: MATH101, INFO202, etc.</small>
                            @error('code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="coefficient">Coefficient <span class="text-danger">*</span></label>
                            <input type="number" name="coefficient" id="coefficient" class="form-control @error('coefficient') is-invalid @enderror" value="{{ old('coefficient', 1) }}" min="1" max="10" required>
                            @error('coefficient')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="couleur">Couleur (optionnel)</label>
                            <input type="color" name="couleur" id="couleur" class="form-control" value="{{ old('couleur', '#007bff') }}" style="height: 50px;">
                            <small class="form-text text-muted">Utilisé pour l'affichage dans le planning</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Attribution aux Filières</h5>
                        
                        <div class="form-group">
                            <label>Filières</label>
                            <div class="border p-3" style="max-height: 250px; overflow-y: auto;">
                                @foreach($filieres as $filiere)
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="filiere_{{ $filiere->id }}" name="filieres[]" value="{{ $filiere->id }}" {{ in_array($filiere->id, old('filieres', [])) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="filiere_{{ $filiere->id }}">
                                            {{ $filiere->nom }} ({{ $filiere->niveau }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>

                        <h5 class="mb-3">Attribution aux Niveaux</h5>
                        
                        <div id="niveaux-container">
                            <button type="button" class="btn btn-sm btn-success mb-2" onclick="addNiveau()">
                                <i class="fas fa-plus"></i> Ajouter un niveau
                            </button>
                            
                            <div id="niveaux-list">
                                <!-- Les niveaux seront ajoutés ici dynamiquement -->
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="{{ route('matieres.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let niveauIndex = 0;

function addNiveau() {
    const container = document.getElementById('niveaux-list');
    const niveauHtml = `
        <div class="card mb-2" id="niveau-${niveauIndex}">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-5">
                        <select name="niveaux[${niveauIndex}][niveau_id]" class="form-control form-control-sm" required>
                            <option value="">Sélectionner un niveau</option>
                            @foreach($niveaux as $niveau)
                                <option value="{{ $niveau->id }}">{{ $niveau->nom }} - {{ $niveau->filiere->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="niveaux[${niveauIndex}][heures_cours]" class="form-control form-control-sm" placeholder="Heures" min="0">
                    </div>
                    <div class="col-md-3">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="obligatoire_${niveauIndex}" name="niveaux[${niveauIndex}][is_obligatoire]" value="1" checked>
                            <label class="custom-control-label" for="obligatoire_${niveauIndex}">Obligatoire</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeNiveau(${niveauIndex})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', niveauHtml);
    niveauIndex++;
}

function removeNiveau(index) {
    document.getElementById(`niveau-${index}`).remove();
}
</script>
@endsection