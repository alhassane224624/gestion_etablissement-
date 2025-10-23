@extends('layouts.app')

@section('title', 'Nouvelle Note')
@section('page-title', 'Ajouter une Nouvelle Note')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="action-card">
                <h5 class="mb-4">
                    <i class="fas fa-plus-circle me-2 text-success"></i>
                    Nouvelle Note
                </h5>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation :</h6>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('notes.store') }}" id="noteForm">
                    @csrf
                    
                    <!-- Champ caché pour classe_id -->
                    <input type="hidden" name="classe_id" id="classeIdInput" value="">

                    <!-- Sélection du stagiaire -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Stagiaire *</label>
                            <select name="stagiaire_id" id="stagiaireSelect" class="form-select @error('stagiaire_id') is-invalid @enderror" required>
                                <option value="">Choisir un stagiaire</option>
                                @foreach($stagiaires as $stagiaire)
                                    <option value="{{ $stagiaire->id }}" 
                                            data-classe="{{ $stagiaire->classe_id }}"
                                            data-niveau="{{ $stagiaire->niveau_id }}"
                                            {{ old('stagiaire_id', $stagiaire->id ?? '') == $stagiaire->id ? 'selected' : '' }}>
                                        {{ $stagiaire->nom }} {{ $stagiaire->prenom }} 
                                        ({{ $stagiaire->matricule }}) - 
                                        {{ $stagiaire->classe->nom ?? 'Aucune classe' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('stagiaire_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Matière et Type -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Matière *</label>
                            <select name="matiere_id" class="form-select @error('matiere_id') is-invalid @enderror" required>
                                <option value="">Choisir une matière</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" 
                                            data-coef="{{ $matiere->coefficient }}"
                                            {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }} (Coef: {{ $matiere->coefficient }})
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Type de note *</label>
                            <select name="type_note" class="form-select @error('type_note') is-invalid @enderror" required>
                                <option value="">Choisir un type</option>
                                <option value="ds" {{ old('type_note') == 'ds' ? 'selected' : '' }}>Devoir Surveillé (DS)</option>
                                <option value="cc" {{ old('type_note') == 'cc' ? 'selected' : '' }}>Contrôle Continu (CC)</option>
                                <option value="examen" {{ old('type_note') == 'examen' ? 'selected' : '' }}>Examen</option>
                                <option value="tp" {{ old('type_note') == 'tp' ? 'selected' : '' }}>Travaux Pratiques (TP)</option>
                                <option value="projet" {{ old('type_note') == 'projet' ? 'selected' : '' }}>Projet</option>
                            </select>
                            @error('type_note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Note et Barème -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Note obtenue *</label>
                            <div class="input-group">
                                <input type="number" name="note" id="noteInput" 
                                       class="form-control @error('note') is-invalid @enderror" 
                                       step="0.01" min="0" max="20" 
                                       value="{{ old('note') }}" 
                                       placeholder="Ex: 15.5" required>
                                <span class="input-group-text">/</span>
                                <input type="number" name="note_sur" id="noteSurInput"
                                       class="form-control @error('note_sur') is-invalid @enderror" 
                                       step="0.1" min="1" max="20" 
                                       value="{{ old('note_sur', 20) }}" 
                                       placeholder="20" required>
                            </div>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Note sur combien ?</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Période</label>
                            <select name="periode_id" class="form-select @error('periode_id') is-invalid @enderror">
                                <option value="">Aucune période</option>
                                @foreach($periodes as $periode)
                                    <option value="{{ $periode->id }}" 
                                            {{ old('periode_id') == $periode->id ? 'selected' : '' }}>
                                        {{ $periode->nom }} 
                                        @if($periode->is_active)
                                            (Active)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('periode_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Note sur 20</label>
                            <div class="form-control-plaintext bg-light rounded p-2 text-center">
                                <strong id="noteSur20Display">-</strong>/20
                            </div>
                            <small class="text-muted">Conversion automatique</small>
                        </div>
                    </div>

                    <!-- Commentaire -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Commentaire (optionnel)</label>
                        <textarea name="commentaire" class="form-control @error('commentaire') is-invalid @enderror" 
                                  rows="3" placeholder="Observations, remarques...">{{ old('commentaire') }}</textarea>
                        @error('commentaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Aperçu -->
                    <div class="border rounded p-3 mb-4 bg-light" id="notePreview" style="display: none;">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-eye me-2"></i>Aperçu de la note
                        </h6>
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Stagiaire :</small>
                                <div id="previewStagiaire" class="fw-bold">-</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Matière :</small>
                                <div id="previewMatiere" class="fw-bold">-</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Note :</small>
                                <div id="previewNote" class="fw-bold text-primary">-</div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <small class="text-muted">Appréciation :</small>
                                <div id="previewAppreciation" class="fw-bold">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('notes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Enregistrer la note
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Guide d'aide -->
        <div class="col-lg-4">
            <div class="action-card">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Guide de saisie
                </h6>
                
                <div class="mb-3">
                    <h6>Types de notes :</h6>
                    <ul class="small">
                        <li><strong>DS :</strong> Devoir surveillé en classe</li>
                        <li><strong>CC :</strong> Contrôle continu</li>
                        <li><strong>Examen :</strong> Épreuve finale</li>
                        <li><strong>TP :</strong> Travaux pratiques</li>
                        <li><strong>Projet :</strong> Évaluation de projet</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6>Barème des appréciations :</h6>
                    <ul class="small">
                        <li>≥ 16/20 : Excellent</li>
                        <li>14-16 : Très bien</li>
                        <li>12-14 : Bien</li>
                        <li>10-12 : Assez bien</li>
                        <li>< 10 : Insuffisant</li>
                    </ul>
                </div>

                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Astuce :</strong> Vous pouvez saisir des notes sur différents barèmes (ex: 15/20, 18/25). La conversion sur 20 est automatique.
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stagiaireSelect = document.getElementById('stagiaireSelect');
    const noteInput = document.getElementById('noteInput');
    const noteSurInput = document.getElementById('noteSurInput');
    const noteSur20Display = document.getElementById('noteSur20Display');
    const preview = document.getElementById('notePreview');
    const classeIdInput = document.getElementById('classeIdInput');

    // Calculer et afficher la note sur 20
    function updateNoteSur20() {
        const note = parseFloat(noteInput.value) || 0;
        const noteSur = parseFloat(noteSurInput.value) || 20;
        
        if (noteSur > 0) {
            const noteSur20 = (note / noteSur) * 20;
            noteSur20Display.textContent = noteSur20.toFixed(2);
            
            // Changer la couleur selon la note
            if (noteSur20 >= 16) {
                noteSur20Display.className = 'text-success fw-bold';
            } else if (noteSur20 >= 10) {
                noteSur20Display.className = 'text-primary fw-bold';
            } else {
                noteSur20Display.className = 'text-danger fw-bold';
            }
        } else {
            noteSur20Display.textContent = '-';
        }
        
        updatePreview();
    }

    // Mise à jour de l'aperçu
    function updatePreview() {
        const stagiaireOption = stagiaireSelect.options[stagiaireSelect.selectedIndex];
        const matiereSelect = document.querySelector('[name="matiere_id"]');
        const matiereOption = matiereSelect.options[matiereSelect.selectedIndex];
        const note = parseFloat(noteInput.value) || 0;
        const noteSur = parseFloat(noteSurInput.value) || 20;
        const noteSur20 = noteSur > 0 ? (note / noteSur) * 20 : 0;

        // Stagiaire
        if (stagiaireSelect.value) {
            document.getElementById('previewStagiaire').textContent = stagiaireOption.text.split('(')[0].trim();
        } else {
            document.getElementById('previewStagiaire').textContent = '-';
        }

        // Matière
        if (matiereSelect.value) {
            document.getElementById('previewMatiere').textContent = matiereOption.text.split('(')[0].trim();
        } else {
            document.getElementById('previewMatiere').textContent = '-';
        }

        // Note
        if (note > 0) {
            document.getElementById('previewNote').innerHTML = 
                `${note.toFixed(2)}/${noteSur} <small class="text-muted">(${noteSur20.toFixed(2)}/20)</small>`;
        } else {
            document.getElementById('previewNote').textContent = '-';
        }

        // Appréciation
        let appreciation = '-';
        if (noteSur20 >= 16) {
            appreciation = 'Excellent travail';
        } else if (noteSur20 >= 14) {
            appreciation = 'Très bon travail';
        } else if (noteSur20 >= 12) {
            appreciation = 'Bon travail';
        } else if (noteSur20 >= 10) {
            appreciation = 'Travail satisfaisant';
        } else if (note > 0) {
            appreciation = 'Travail insuffisant';
        }
        document.getElementById('previewAppreciation').textContent = appreciation;

        // Afficher l'aperçu si au moins un champ est rempli
        preview.style.display = (stagiaireSelect.value || matiereSelect.value || note > 0) ? 'block' : 'none';
    }

    // Event listeners
    noteInput.addEventListener('input', updateNoteSur20);
    noteSurInput.addEventListener('input', updateNoteSur20);
    stagiaireSelect.addEventListener('change', updatePreview);
    document.querySelector('[name="matiere_id"]').addEventListener('change', updatePreview);

    // Validation des notes
    noteInput.addEventListener('change', function() {
        const note = parseFloat(this.value);
        const noteSur = parseFloat(noteSurInput.value) || 20;
        
        if (note > noteSur) {
            alert(`La note ne peut pas dépasser ${noteSur}`);
            this.value = noteSur;
            updateNoteSur20();
        }
    });

    // Auto-sélection de la classe selon le stagiaire
    stagiaireSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const classeId = selectedOption.getAttribute('data-classe');
        
        // Mettre à jour le champ caché classe_id
        classeIdInput.value = classeId || '';
    });

    // Initialiser la classe si un stagiaire est déjà sélectionné
    if (stagiaireSelect.value) {
        const selectedOption = stagiaireSelect.options[stagiaireSelect.selectedIndex];
        const classeId = selectedOption.getAttribute('data-classe');
        classeIdInput.value = classeId || '';
    }

    // Initialiser
    updateNoteSur20();
    updatePreview();
});
</script>
@endpush