@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-user-times"></i> Enregistrer une Absence</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('absences.index') }}">Absences</a></li>
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
            <form action="{{ route('absences.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations de l'absence</h5>

                        <div class="form-group">
                            <label for="stagiaire_id">Stagiaire <span class="text-danger">*</span></label>
                            <select name="stagiaire_id" id="stagiaire_id" class="form-control @error('stagiaire_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un stagiaire</option>
                                @foreach($stagiaires as $stagiaire)
                                    <option value="{{ $stagiaire->id }}" {{ old('stagiaire_id', $stagiaire_id ?? '') == $stagiaire->id ? 'selected' : '' }}>
                                        {{ $stagiaire->nom_complet }} - {{ $stagiaire->filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('stagiaire_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                            @error('date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Type d'absence <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">Sélectionner un type</option>
                                <option value="matin" {{ old('type') == 'matin' ? 'selected' : '' }}>Matin</option>
                                <option value="apres_midi" {{ old('type') == 'apres_midi' ? 'selected' : '' }}>Après-midi</option>
                                <option value="journee" {{ old('type') == 'journee' ? 'selected' : '' }}>Journée complète</option>
                                <option value="heure" {{ old('type') == 'heure' ? 'selected' : '' }}>Par heure</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="heures-container" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="heure_debut">Heure début</label>
                                        <input type="time" name="heure_debut" id="heure_debut" class="form-control @error('heure_debut') is-invalid @enderror" value="{{ old('heure_debut') }}">
                                        @error('heure_debut')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="heure_fin">Heure fin</label>
                                        <input type="time" name="heure_fin" id="heure_fin" class="form-control @error('heure_fin') is-invalid @enderror" value="{{ old('heure_fin') }}">
                                        @error('heure_fin')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Justification</h5>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="justifiee" name="justifiee" value="1" {{ old('justifiee') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="justifiee">Absence justifiée</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="motif">Motif</label>
                            <textarea name="motif" id="motif" rows="4" class="form-control @error('motif') is-invalid @enderror">{{ old('motif') }}</textarea>
                            <small class="form-text text-muted">Expliquez la raison de l'absence</small>
                            @error('motif')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="document_justificatif">Document justificatif</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('document_justificatif') is-invalid @enderror" id="document_justificatif" name="document_justificatif" accept=".pdf,.jpg,.jpeg,.png">
                                <label class="custom-file-label" for="document_justificatif">Choisir un fichier...</label>
                                @error('document_justificatif')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted">PDF, JPG, JPEG ou PNG - Max 2Mo</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Un document justificatif peut être ajouté ultérieurement si nécessaire.
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="{{ route('absences.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Afficher/masquer les champs d'heure selon le type
document.getElementById('type').addEventListener('change', function() {
    const heuresContainer = document.getElementById('heures-container');
    const heureDebut = document.getElementById('heure_debut');
    const heureFin = document.getElementById('heure_fin');
    
    if(this.value === 'heure') {
        heuresContainer.style.display = 'block';
        heureDebut.required = true;
        heureFin.required = true;
    } else {
        heuresContainer.style.display = 'none';
        heureDebut.required = false;
        heureFin.required = false;
    }
});

// Trigger au chargement
if(document.getElementById('type').value === 'heure') {
    document.getElementById('heures-container').style.display = 'block';
}

// Mettre à jour le label du fichier
document.getElementById('document_justificatif').addEventListener('change', function() {
    const fileName = this.files[0] ? this.files[0].name : 'Choisir un fichier...';
    this.nextElementSibling.textContent = fileName;
});
</script>
@endsection