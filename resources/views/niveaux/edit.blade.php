@extends('layouts.app')

@section('title', 'Modifier le Niveau')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit"></i> Modifier le Niveau: {{ $niveau->nom }}
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

    <form action="{{ route('niveaux.update', $niveau) }}" method="POST">
        @csrf
        @method('PUT')
        
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
                                           value="{{ old('nom', $niveau->nom) }}"
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
                                           value="{{ old('ordre', $niveau->ordre) }}"
                                           min="1"
                                           required>
                                    @error('ordre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="duree_semestres">Durée <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('duree_semestres') is-invalid @enderror" 
                                           id="duree_semestres" 
                                           name="duree_semestres" 
                                           value="{{ old('duree_semestres', $niveau->duree_semestres) }}"
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
                                    <option value="{{ $filiere->id }}" 
                                            {{ old('filiere_id', $niveau->filiere_id) == $filiere->id ? 'selected' : '' }}>
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
                            <i class="fas fa-book"></i> Matières du Niveau
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="matieres-container">
                            @foreach($niveau->matieres as $index => $matiere)
                                <div class="matiere-row mb-3 border p-3 rounded">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group mb-2">
                                                <label>Matière</label>
                                                <select class="form-control" name="matieres[{{ $index }}][matiere_id]">
                                                    <option value="">Sélectionner une matière</option>
                                                    @foreach($matieres as $m)
                                                        <option value="{{ $m->id }}" {{ $matiere->id == $m->id ? 'selected' : '' }}>
                                                            {{ $m->nom }} ({{ $m->code }})
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
                                                       name="matieres[{{ $index }}][heures_cours]"
                                                       value="{{ $matiere->pivot->heures_cours }}"
                                                       min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label>Obligatoire?</label>
                                                <select class="form-control" name="matieres[{{ $index }}][is_obligatoire]">
                                                    <option value="1" {{ $matiere->pivot->is_obligatoire ? 'selected' : '' }}>Oui</option>
                                                    <option value="0" {{ !$matiere->pivot->is_obligatoire ? 'selected' : '' }}>Non</option>
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
                            @endforeach

                            @if($niveau->matieres->isEmpty())
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
                                                <input type="number" class="form-control" name="matieres[0][heures_cours]" min="0">
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
                            @endif
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
                            <i class="fas fa-chart-pie"></i> Statistiques
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small text-muted">Classes</label>
                            <h5 class="text-info">{{ $niveau->classes->count() }}</h5>
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted">Stagiaires</label>
                            <h5 class="text-success">{{ $niveau->stagiaires->count() }}</h5>
                        </div>

                        <div class="mb-0">
                            <label class="small text-muted">Matières Actuelles</label>
                            <h5 class="text-warning">{{ $niveau->matieres->count() }}</h5>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Enregistrer les Modifications
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
let matiereIndex = {{ $niveau->matieres->count() > 0 ? $niveau->matieres->count() : 1 }};

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
                        <input type="number" class="form-control" name="matieres[${matiereIndex}][heures_cours]" min="0">
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
    matiereRow.remove();
}
</script>
@endpush