 
@extends('layouts.app-professeur')

@section('title', 'Créer un Cours')
@section('page-title', 'Planifier un Nouveau Cours')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 fw-bold">
                                <i class="fas fa-calendar-plus text-primary me-2"></i>
                                Créer un Nouveau Cours
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Planifiez un cours pour vos stagiaires
                            </p>
                        </div>
                        <a href="{{ route('professeur.planning') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Retour au Planning
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de création -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form action="{{ route('professeur.planning.store') }}" method="POST" id="planningForm">
                @csrf

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-edit me-2"></i>
                            Informations du Cours
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-label fw-semibold">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        Date du Cours <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           name="date" 
                                           id="date" 
                                           class="form-control form-control-lg @error('date') is-invalid @enderror" 
                                           value="{{ old('date', now()->format('Y-m-d')) }}"
                                           min="{{ now()->format('Y-m-d') }}"
                                           required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Aujourd'hui: {{ now()->isoFormat('dddd D MMMM YYYY') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Type de cours -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_cours" class="form-label fw-semibold">
                                        <i class="fas fa-bookmark text-info me-1"></i>
                                        Type de Cours <span class="text-danger">*</span>
                                    </label>
                                    <select name="type_cours" 
                                            id="type_cours" 
                                            class="form-select form-select-lg @error('type_cours') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Sélectionner le type --</option>
                                        <option value="cours" {{ old('type_cours') == 'cours' ? 'selected' : '' }}>
                                            📖 Cours Magistral
                                        </option>
                                        <option value="td" {{ old('type_cours') == 'td' ? 'selected' : '' }}>
                                            ✏️ Travaux Dirigés (TD)
                                        </option>
                                        <option value="tp" {{ old('type_cours') == 'tp' ? 'selected' : '' }}>
                                            🔬 Travaux Pratiques (TP)
                                        </option>
                                        <option value="examen" {{ old('type_cours') == 'examen' ? 'selected' : '' }}>
                                            📝 Examen
                                        </option>
                                    </select>
                                    @error('type_cours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Horaires -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="heure_debut" class="form-label fw-semibold">
                                        <i class="fas fa-clock text-success me-1"></i>
                                        Heure de Début <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" 
                                           name="heure_debut" 
                                           id="heure_debut" 
                                           class="form-control form-control-lg @error('heure_debut') is-invalid @enderror" 
                                           value="{{ old('heure_debut', '08:00') }}"
                                           required>
                                    @error('heure_debut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="heure_fin" class="form-label fw-semibold">
                                        <i class="fas fa-clock text-warning me-1"></i>
                                        Heure de Fin <span class="text-danger">*</span>
                                    </label>
                                    <input type="time" 
                                           name="heure_fin" 
                                           id="heure_fin" 
                                           class="form-control form-control-lg @error('heure_fin') is-invalid @enderror" 
                                           value="{{ old('heure_fin', '10:00') }}"
                                           required>
                                    @error('heure_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted" id="duree-info"></small>
                                </div>
                            </div>

                            <!-- Classe -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="classe_id" class="form-label fw-semibold">
                                        <i class="fas fa-users text-purple me-1"></i>
                                        Classe <span class="text-danger">*</span>
                                    </label>
                                    <select name="classe_id" 
                                            id="classe_id" 
                                            class="form-select form-select-lg select2 @error('classe_id') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Sélectionner une classe --</option>
                                        @foreach($classes as $classe)
                                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                                {{ $classe->nom }} - {{ $classe->filiere->nom ?? '' }} 
                                                ({{ $classe->niveau->nom ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('classe_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($classes->count() == 0)
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Aucune classe disponible
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <!-- Matière -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="matiere_id" class="form-label fw-semibold">
                                        <i class="fas fa-book text-danger me-1"></i>
                                        Matière <span class="text-danger">*</span>
                                    </label>
                                    <select name="matiere_id" 
                                            id="matiere_id" 
                                            class="form-select form-select-lg select2 @error('matiere_id') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Sélectionner une matière --</option>
                                        @foreach($matieres as $matiere)
                                            <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                                {{ $matiere->nom }} 
                                                @if($matiere->code)
                                                    ({{ $matiere->code }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('matiere_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($matieres->count() == 0)
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Aucune matière assignée
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <!-- Salle -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="salle_id" class="form-label fw-semibold">
                                        <i class="fas fa-door-open text-teal me-1"></i>
                                        Salle <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <select name="salle_id" 
                                                id="salle_id" 
                                                class="form-select select2 @error('salle_id') is-invalid @enderror" 
                                                required>
                                            <option value="">-- Sélectionner une salle --</option>
                                            @foreach($salles as $salle)
                                                <option value="{{ $salle->id }}" 
                                                        data-capacite="{{ $salle->capacite }}"
                                                        data-type="{{ $salle->type }}"
                                                        {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                                    {{ $salle->nom }} 
                                                    (Capacité: {{ $salle->capacite }} - {{ ucfirst($salle->type ?? 'Standard') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" 
                                                class="btn btn-outline-secondary" 
                                                id="checkDisponibilite"
                                                title="Vérifier la disponibilité">
                                            <i class="fas fa-search"></i>
                                            Vérifier
                                        </button>
                                    </div>
                                    @error('salle_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div id="salle-info" class="mt-2"></div>
                                    @if($salles->count() == 0)
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Aucune salle disponible
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="fas fa-align-left text-secondary me-1"></i>
                                        Description / Notes (Optionnel)
                                    </label>
                                    <textarea name="description" 
                                              id="description" 
                                              rows="4" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              placeholder="Ajoutez des notes ou des informations complémentaires sur ce cours...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <span id="char-count">0</span> / 1000 caractères
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Les champs marqués d'un <span class="text-danger">*</span> sont obligatoires
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('professeur.planning') }}" class="btn btn-lg btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-lg btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i>
                                    Créer le Cours
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.text-purple { color: #9333ea; }
.text-teal { color: #14b8a6; }

.form-control-lg, .form-select-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border-radius: 0.5rem;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
}

.card {
    transition: all 0.3s ease;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}

.select2-container--bootstrap-5 .select2-selection {
    min-height: 48px;
    padding: 0.375rem 0.75rem;
}

#salle-info {
    padding: 12px;
    border-radius: 8px;
    font-size: 0.9rem;
}

#salle-info.disponible {
    background-color: #d1fae5;
    border: 1px solid #10b981;
    color: #065f46;
}

#salle-info.indisponible {
    background-color: #fee2e2;
    border: 1px solid #ef4444;
    color: #991b1b;
}

/* Animation du bouton submit */
#submitBtn {
    position: relative;
    overflow: hidden;
}

#submitBtn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

#submitBtn:hover::after {
    width: 300px;
    height: 300px;
}

/* Loader */
.btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

.btn.loading::before {
    content: '';
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-right: 8px;
    border: 2px solid #fff;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialiser Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Sélectionner...'
    });

    // Calculer la durée du cours
    function calculerDuree() {
        const heureDebut = $('#heure_debut').val();
        const heureFin = $('#heure_fin').val();
        
        if (heureDebut && heureFin) {
            const debut = new Date('2000-01-01 ' + heureDebut);
            const fin = new Date('2000-01-01 ' + heureFin);
            const diff = (fin - debut) / 1000 / 60; // en minutes
            
            if (diff > 0) {
                const heures = Math.floor(diff / 60);
                const minutes = diff % 60;
                let texte = '<i class="fas fa-hourglass-half me-1"></i>Durée: ';
                
                if (heures > 0) texte += heures + 'h ';
                if (minutes > 0) texte += minutes + 'min';
                
                $('#duree-info').html(texte).removeClass('text-danger').addClass('text-success');
            } else {
                $('#duree-info').html('<i class="fas fa-exclamation-triangle me-1"></i>L\'heure de fin doit être après l\'heure de début')
                    .removeClass('text-success').addClass('text-danger');
            }
        }
    }

    $('#heure_debut, #heure_fin').on('change', calculerDuree);
    calculerDuree();

    // Compteur de caractères
    $('#description').on('input', function() {
        const count = $(this).val().length;
        $('#char-count').text(count);
        
        if (count > 1000) {
            $('#char-count').addClass('text-danger');
        } else {
            $('#char-count').removeClass('text-danger');
        }
    });

    // Vérifier la disponibilité de la salle
    $('#checkDisponibilite').on('click', function() {
        const salleId = $('#salle_id').val();
        const date = $('#date').val();
        const heureDebut = $('#heure_debut').val();
        const heureFin = $('#heure_fin').val();
        
        if (!salleId || !date || !heureDebut || !heureFin) {
            $('#salle-info').html('<i class="fas fa-info-circle me-1"></i>Veuillez remplir tous les champs requis')
                .removeClass('disponible indisponible')
                .css({
                    'background-color': '#fef3c7',
                    'border': '1px solid #f59e0b',
                    'color': '#92400e'
                });
            return;
        }
        
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Vérification...');
        
        $.ajax({
            url: '{{ route("salles.check-disponibilite") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                salle_id: salleId,
                date: date,
                heure_debut: heureDebut,
                heure_fin: heureFin
            },
            success: function(response) {
                if (response.disponible) {
                    $('#salle-info').html('<i class="fas fa-check-circle me-1"></i>' + response.message)
                        .removeClass('indisponible').addClass('disponible');
                } else {
                    $('#salle-info').html('<i class="fas fa-times-circle me-1"></i>' + response.message)
                        .removeClass('disponible').addClass('indisponible');
                }
            },
            error: function() {
                $('#salle-info').html('<i class="fas fa-exclamation-triangle me-1"></i>Erreur lors de la vérification')
                    .removeClass('disponible').addClass('indisponible');
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-search me-1"></i>Vérifier');
            }
        });
    });

    // Validation du formulaire
    $('#planningForm').on('submit', function(e) {
        const heureDebut = $('#heure_debut').val();
        const heureFin = $('#heure_fin').val();
        
        if (heureDebut && heureFin) {
            const debut = new Date('2000-01-01 ' + heureDebut);
            const fin = new Date('2000-01-01 ' + heureFin);
            
            if (fin <= debut) {
                e.preventDefault();
                alert('L\'heure de fin doit être après l\'heure de début');
                return false;
            }
        }
        
        // Animation du bouton
        $('#submitBtn').addClass('loading').html('<span class="spinner-border spinner-border-sm me-2"></span>Création en cours...');
    });

    // Afficher les infos de la salle sélectionnée
    $('#salle_id').on('change', function() {
        const option = $(this).find('option:selected');
        const capacite = option.data('capacite');
        const type = option.data('type');
        
        if (capacite) {
            $('#salle-info').html(
                '<i class="fas fa-info-circle me-1"></i>' +
                '<strong>Capacité:</strong> ' + capacite + ' personnes | ' +
                '<strong>Type:</strong> ' + (type ? type : 'Standard')
            ).css({
                'background-color': '#e0e7ff',
                'border': '1px solid #6366f1',
                'color': '#3730a3'
            }).removeClass('disponible indisponible');
        } else {
            $('#salle-info').html('').css('border', 'none');
        }
    });
});
</script>
@endpush
@endsection