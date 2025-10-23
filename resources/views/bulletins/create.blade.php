@extends('layouts.app')

@section('title', 'Nouveau Cours')
@section('page-title', 'Programmer un Nouveau Cours')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="action-card">
                <h5 class="mb-4">
                    <i class="fas fa-calendar-plus me-2 text-success"></i>
                    Nouveau Cours au Planning
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

                <form method="POST" action="{{ route('planning.store') }}" id="planningForm">
                    @csrf

                    <!-- Informations de base -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Filière *</label>
                            <select name="filiere_id" class="form-select @error('filiere_id') is-invalid @enderror" required>
                                <option value="">Choisir une filière</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ old('filiere_id', request('filiere_id')) == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }} - {{ $filiere->niveau }}
                                    </option>
                                @endforeach
                            </select>
                            @error('filiere_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Professeur *</label>
                            <select name="professeur_id" id="professeurSelect" class="form-select @error('professeur_id') is-invalid @enderror" required>
                                <option value="">Choisir un professeur</option>
                                @foreach($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" 
                                            data-matieres="{{ $professeur->matieres->pluck('matiere')->join(',') }}"
                                            {{ old('professeur_id', request('professeur_id')) == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('professeur_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Matière *</label>
                            <select name="matiere" id="matiereSelect" class="form-select @error('matiere') is-invalid @enderror" required>
                                <option value="">D'abord choisir un professeur</option>
                            </select>
                            @error('matiere')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Type de cours *</label>
                            <select name="type_cours" class="form-select @error('type_cours') is-invalid @enderror" required>
                                <option value="">Sélectionner le type</option>
                                <option value="cours" {{ old('type_cours') == 'cours' ? 'selected' : '' }}>Cours magistral</option>
                                <option value="td" {{ old('type_cours') == 'td' ? 'selected' : '' }}>Travaux dirigés (TD)</option>
                                <option value="tp" {{ old('type_cours') == 'tp' ? 'selected' : '' }}>Travaux pratiques (TP)</option>
                                <option value="examen" {{ old('type_cours') == 'examen' ? 'selected' : '' }}>Examen</option>
                            </select>
                            @error('type_cours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Date et horaires -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Date *</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', request('date', date('Y-m-d'))) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Heure début *</label>
                            <input type="time" name="heure_debut" class="form-control @error('heure_debut') is-invalid @enderror" 
                                   value="{{ old('heure_debut', request('heure', '08:00')) }}" required>
                            @error('heure_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Heure fin *</label>
                            <input type="time" name="heure_fin" class="form-control @error('heure_fin') is-invalid @enderror" 
                                   value="{{ old('heure_fin') }}" required>
                            @error('heure_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Salle -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Salle *</label>
                            <select name="salle_id" id="salleSelect" class="form-select @error('salle_id') is-invalid @enderror" required>
                                <option value="">Choisir une salle</option>
                                @foreach($salles as $salle)
                                    <option value="{{ $salle->id }}" 
                                            data-capacite="{{ $salle->capacite }}"
                                            data-type="{{ $salle->type }}"
                                            data-equipements="{{ implode(', ', $salle->equipements ?? []) }}"
                                            {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                        {{ $salle->nom }} 
                                        ({{ $salle->type_libelle }}, Cap: {{ $salle->capacite }})
                                        @if($salle->batiment) - {{ $salle->batiment }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('salle_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="salleInfo" class="mt-2" style="display: none;">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="salleDetails"></span>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Description (optionnelle) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Description/Notes (optionnel)</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" placeholder="Ajoutez des détails sur le cours, les objectifs, le matériel requis, etc.">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Zone de vérification des conflits -->
                    <div id="conflitsZone" style="display: none;" class="alert alert-warning mb-4">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Conflits détectés :</h6>
                        <div id="conflitsListe"></div>
                    </div>

                    <!-- Suggestions horaires -->
                    <div class="bg-light p-3 rounded mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-clock me-2"></i>Créneaux suggérés
                        </h6>
                        <div class="btn-group btn-group-sm flex-wrap" id="creneauxSuggeres">
                            <button type="button" class="btn btn-outline-primary" onclick="setHoraire('08:00', '09:30')">
                                08:00 - 09:30
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="setHoraire('09:45', '11:15')">
                                09:45 - 11:15
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="setHoraire('11:30', '13:00')">
                                11:30 - 13:00
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="setHoraire('14:00', '15:30')">
                                14:00 - 15:30
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="setHoraire('15:45', '17:15')">
                                15:45 - 17:15
                            </button>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('planning.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <div>
                            <button type="button" class="btn btn-warning" onclick="verifierConflits()">
                                <i class="fas fa-search me-2"></i>Vérifier conflits
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Enregistrer le cours
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const professeurSelect = document.getElementById('professeurSelect');
    const matiereSelect = document.getElementById('matiereSelect');
    const salleSelect = document.getElementById('salleSelect');
    const salleInfo = document.getElementById('salleInfo');
    const salleDetails = document.getElementById('salleDetails');

    // Gestion des matières selon le professeur sélectionné
    professeurSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const matieres = selectedOption.getAttribute('data-matieres');
        
        // Vider la liste des matières
        matiereSelect.innerHTML = '<option value="">Choisir une matière</option>';
        
        if (matieres) {
            const matieresList = matieres.split(',');
            matieresList.forEach(matiere => {
                if (matiere.trim()) {
                    const option = document.createElement('option');
                    option.value = matiere.trim();
                    option.textContent = matiere.trim();
                    matiereSelect.appendChild(option);
                }
            });
        }
    });

    // Affichage des détails de la salle
    salleSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const capacite = selectedOption.getAttribute('data-capacite');
            const type = selectedOption.getAttribute('data-type');
            const equipements = selectedOption.getAttribute('data-equipements');
            
            salleDetails.innerHTML = `
                Capacité: ${capacite} places | 
                Type: ${type} | 
                Équipements: ${equipements || 'Aucun'}
            `;
            salleInfo.style.display = 'block';
        } else {
            salleInfo.style.display = 'none';
        }
    });

    // Auto-vérification des conflits lors des changements
    ['professeur_id', 'salle_id', 'filiere_id', 'date', 'heure_debut', 'heure_fin'].forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.addEventListener('change', debounce(verifierConflits, 500));
        }
    });
});

function setHoraire(debut, fin) {
    document.querySelector('[name="heure_debut"]').value = debut;
    document.querySelector('[name="heure_fin"]').value = fin;
    verifierConflits();
}

function verifierConflits() {
    const formData = new FormData(document.getElementById('planningForm'));
    const conflitsZone = document.getElementById('conflitsZone');
    const conflitsListe = document.getElementById('conflitsListe');

    // Vérifier que les champs essentiels sont remplis
    const requiredFields = ['professeur_id', 'salle_id', 'filiere_id', 'date', 'heure_debut', 'heure_fin'];
    const missingFields = requiredFields.filter(field => !formData.get(field));
    
    if (missingFields.length > 0) {
        conflitsZone.style.display = 'none';
        return;
    }

    // Appel API pour vérifier les conflits
    fetch('/api/planning/check-conflicts', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.has_conflict) {
            conflitsListe.innerHTML = `<i class="fas fa-times-circle text-danger me-2"></i>${data.message}`;
            conflitsZone.style.display = 'block';
            conflitsZone.className = 'alert alert-danger mb-4';
        } else {
            conflitsListe.innerHTML = `<i class="fas fa-check-circle text-success me-2"></i>Aucun conflit détecté. Vous pouvez programmer ce cours.`;
            conflitsZone.style.display = 'block';
            conflitsZone.className = 'alert alert-success mb-4';
        }
    })
    .catch(error => {
        console.error('Erreur lors de la vérification:', error);
    });
}

// Fonction utilitaire pour limiter les appels
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Initialiser les matières si un professeur est déjà sélectionné
if (document.getElementById('professeurSelect').value) {
    document.getElementById('professeurSelect').dispatchEvent(new Event('change'));
}

// Initialiser les détails de la salle si une salle est déjà sélectionnée
if (document.getElementById('salleSelect').value) {
    document.getElementById('salleSelect').dispatchEvent(new Event('change'));
}
</script>
@endpush