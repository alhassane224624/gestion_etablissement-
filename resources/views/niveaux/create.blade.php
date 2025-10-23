@extends('layouts.app')

@section('title', 'Créer un Niveau')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle"></i> Créer un Niveau
        </h1>
        <a href="{{ route('niveaux.index') }}" class="btn btn-secondary">
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

    <form action="{{ route('niveaux.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle"></i> Informations du Niveau
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nom">Nom du niveau <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom') }}"
                                           placeholder="Ex: 1ère Année"
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ordre">Ordre <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('ordre') is-invalid @enderror" 
                                           id="ordre" 
                                           name="ordre" 
                                           value="{{ old('ordre', 1) }}"
                                           min="1"
                                           required>
                                    @error('ordre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Position dans la filière</small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="duree_semestres">Durée <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('duree_semestres') is-invalid @enderror" 
                                           id="duree_semestres" 
                                           name="duree_semestres" 
                                           value="{{ old('duree_semestres', 2) }}"
                                           min="1"
                                           max="10"
                                           required>
                                    @error('duree_semestres')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">En semestres</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="filiere_id">Filière <span class="text-danger">*</span></label>
                            <select class="form-control @error('filiere_id') is-invalid @enderror" 
                                    id="filiere_id" 
                                    name="filiere_id"
                                    required>
                                <option value="">Sélectionner une filière</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }} - {{ $filiere->niveau }}
                                    </option>
                                @endforeach
                            </select>
                            @error('filiere_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Matières -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-book"></i> Matières du Niveau (Optionnel)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="matieres-container">
                            <div class="matiere-row mb-3 border p-3 rounded">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group mb-2">
                                            <label>Matière</label>
                                            <select class="form-control" name="matieres[0][matiere_id]">
                                                <option value="">Sélectionner une matière</option>
                                                @foreach($matieres as $matiere)
                                                    <option value="{{ $matiere->id }}">
                                                        {{ $matiere->nom }} ({{ $matiere->code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-2">
                                            <label>Heures de cours</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="matieres[0][heures_cours]"
                                                   min="0"
                                                   placeholder="Ex: 60">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-2">
                                            <label>Obligatoire?</label>
                                            <select class="form-control" name="matieres[0][is_obligatoire]">
                                                <option value="1">Oui</option>
                                                <option value="0">Non</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm mb-2" onclick="removeMatiere(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-success" onclick="addMatiere()">
                            <i class="fas fa-plus"></i> Ajouter une matière
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-info-circle"></i> Aide
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold">Qu'est-ce qu'un niveau?</h6>
                        <p class="small text-muted">
                            Un niveau représente une année d'étude dans une filière. Par exemple: 1ère année, 2ème année, etc.
                        </p>

                        <hr>

                        <h6 class="font-weight-bold">Ordre:</h6>
                        <p class="small text-muted">
                            L'ordre définit la progression dans la filière. La 1ère année aura l'ordre 1, la 2ème année l'ordre 2, etc.
                        </p>

                        <hr>

                        <h6 class="font-weight-bold">Durée:</h6>
                        <p class="small text-muted">
                            Nombre de semestres pour ce niveau. Généralement 2 semestres = 1 année scolaire.
                        </p>

                        <hr>

                        <h6 class="font-weight-bold">Matières:</h6>
                        <p class="small mb-0 text-muted">
                            Vous pouvez associer les matières enseignées dans ce niveau. Cette association est optionnelle et peut être faite plus tard.
                        </p>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Enregistrer le Niveau
                        </button>
                        <a href="{{ route('niveaux.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let matiereIndex = 1;

function addMatiere() {
    const container = document.getElementById('matieres-container');
    const newMatiere = `
        <div class="matiere-row mb-3 border p-3 rounded">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group mb-2">
                        <label>Matière</label>
                        <select class="form-control" name="matieres[${matiereIndex}][matiere_id]">
                            <option value="">Sélectionner une matière</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}">{{ $matiere->nom }} ({{ $matiere->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-2">
                        <label>Heures de cours</label>
                        <input type="number" class="form-control" name="matieres[${matiereIndex}][heures_cours]" min="0" placeholder="Ex: 60">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-2">
                        <label>Obligatoire?</label>
                        <select class="form-control" name="matieres[${matiereIndex}][is_obligatoire]">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm mb-2" onclick="removeMatiere(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newMatiere);
    matiereIndex++;
}

function removeMatiere(button) {
    const matiereRow = button.closest('.matiere-row');
    if (document.querySelectorAll('.matiere-row').length > 1) {
        matiereRow.remove();
    } else {
        alert('Vous devez garder au moins une ligne de matière.');
    }
}
</script>
@endpush