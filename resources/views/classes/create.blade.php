@extends('layouts.app')

@section('title', 'Nouvelle Classe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle"></i> Nouvelle Classe
        </h1>
        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informations de la Classe
                    </h6>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Erreurs de validation :</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('classes.store') }}">
                        @csrf

                        <!-- Nom de la classe -->
                        <div class="form-group">
                            <label for="nom" class="font-weight-bold">
                                Nom de la classe <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}" 
                                   placeholder="Ex: 1ère année, 2ème année A..."
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Entrez un nom descriptif pour la classe
                            </small>
                        </div>

                        <!-- Filière -->
                        <div class="form-group">
                            <label for="filiere_id" class="font-weight-bold">
                                Filière <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('filiere_id') is-invalid @enderror" 
                                    id="filiere_id" 
                                    name="filiere_id" 
                                    required>
                                <option value="">Choisir une filière</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('filiere_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Niveau -->
                        <div class="form-group">
                            <label for="niveau_id" class="font-weight-bold">
                                Niveau <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('niveau_id') is-invalid @enderror" 
                                    id="niveau_id" 
                                    name="niveau_id" 
                                    required>
                                <option value="">Choisir un niveau</option>
                                @foreach($niveaux as $niveau)
                                    <option value="{{ $niveau->id }}" 
                                            data-filiere="{{ $niveau->filiere_id }}"
                                            {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>
                                        {{ $niveau->nom }} - {{ $niveau->filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('niveau_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Année Scolaire -->
                        <div class="form-group">
                            <label for="annee_scolaire_id" class="font-weight-bold">
                                Année Scolaire <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('annee_scolaire_id') is-invalid @enderror" 
                                    id="annee_scolaire_id" 
                                    name="annee_scolaire_id" 
                                    required>
                                <option value="">Choisir une année scolaire</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}" {{ old('annee_scolaire_id') == $annee->id ? 'selected' : '' }}>
                                        {{ $annee->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('annee_scolaire_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Effectif Maximum -->
                        <div class="form-group">
                            <label for="effectif_max" class="font-weight-bold">
                                Effectif Maximum <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('effectif_max') is-invalid @enderror" 
                                   id="effectif_max" 
                                   name="effectif_max" 
                                   value="{{ old('effectif_max', 30) }}" 
                                   min="1" 
                                   max="100" 
                                   required>
                            @error('effectif_max')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Nombre maximum d'élèves dans cette classe (entre 1 et 100)
                            </small>
                        </div>

                        <!-- Boutons -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                            <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Aide -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-lightbulb"></i> Aide
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">Conseils :</h6>
                    <ul class="small">
                        <li>Le <strong>nom de la classe</strong> doit être descriptif et unique</li>
                        <li>Choisissez la <strong>filière</strong> appropriée</li>
                        <li>Sélectionnez le <strong>niveau</strong> correspondant</li>
                        <li>Définissez un <strong>effectif maximum</strong> réaliste</li>
                    </ul>

                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fas fa-info-circle"></i>
                            Les champs marqués d'une <span class="text-danger">*</span> sont obligatoires
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Filtrer les niveaux selon la filière sélectionnée
document.getElementById('filiere_id').addEventListener('change', function() {
    const filiereId = this.value;
    const niveauSelect = document.getElementById('niveau_id');
    const niveauOptions = niveauSelect.querySelectorAll('option');
    
    niveauOptions.forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
            return;
        }
        
        const optionFiliereId = option.getAttribute('data-filiere');
        if (filiereId === '' || optionFiliereId === filiereId) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });
    
    // Réinitialiser la sélection si le niveau ne correspond pas à la filière
    const selectedOption = niveauSelect.options[niveauSelect.selectedIndex];
    if (selectedOption.getAttribute('data-filiere') !== filiereId && filiereId !== '') {
        niveauSelect.value = '';
    }
});

// Déclencher le filtrage au chargement si une filière est déjà sélectionnée
if (document.getElementById('filiere_id').value) {
    document.getElementById('filiere_id').dispatchEvent(new Event('change'));
}
</script>
@endpush