@extends('layouts.app')

@section('title', 'Nouvelle Période')
@section('page-title', 'Créer une Nouvelle Période Scolaire')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="action-card">
                <h5 class="mb-4">
                    <i class="fas fa-calendar-plus me-2 text-success"></i>
                    Nouvelle Période Scolaire
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

                <form method="POST" action="{{ route('periodes.store') }}">
                    @csrf

                    <!-- Informations de base -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Année scolaire *</label>
                            <select name="annee_scolaire_id" class="form-select @error('annee_scolaire_id') is-invalid @enderror" required>
                                <option value="">Choisir une année scolaire</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}" {{ old('annee_scolaire_id', request('annee_id')) == $annee->id ? 'selected' : '' }}>
                                        {{ $annee->nom }}
                                        @if($annee->is_active)
                                            <span>(Active)</span>
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('annee_scolaire_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom de la période *</label>
                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                                   value="{{ old('nom') }}" placeholder="Ex: Semestre 1, Trimestre 2" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Type de période *</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" value="semestre" 
                                               id="semestre" {{ old('type') == 'semestre' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="semestre">
                                            <strong>Semestre</strong>
                                            <br><small class="text-muted">Période de 6 mois environ</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" value="trimestre" 
                                               id="trimestre" {{ old('type') == 'trimestre' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="trimestre">
                                            <strong>Trimestre</strong>
                                            <br><small class="text-muted">Période de 3 mois environ</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" value="periode" 
                                               id="periode" {{ old('type') == 'periode' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="periode">
                                            <strong>Période personnalisée</strong>
                                            <br><small class="text-muted">Durée libre</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date de début *</label>
                            <input type="date" name="debut" class="form-control @error('debut') is-invalid @enderror" 
                                   value="{{ old('debut') }}" required>
                            @error('debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date de fin *</label>
                            <input type="date" name="fin" class="form-control @error('fin') is-invalid @enderror" 
                                   value="{{ old('fin') }}" required>
                            @error('fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="bg-light p-3 rounded">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                           {{ old('is_active') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_active">
                                        Activer cette période immédiatement
                                    </label>
                                    <br><small class="text-muted">
                                        Si coché, cette période sera définie comme active et les autres périodes de la même année seront désactivées.
                                        Seule une période active peut recevoir de nouvelles notes.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu des informations -->
                    <div class="border rounded p-3 mb-4" id="periodePreview" style="display: none;">
                        <h6 class="text-primary">
                            <i class="fas fa-eye me-2"></i>Aperçu de la période
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Nom :</small>
                                <div id="previewNom" class="fw-bold">-</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Type :</small>
                                <div id="previewType" class="fw-bold">-</div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <small class="text-muted">Début :</small>
                                <div id="previewDebut" class="fw-bold">-</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Fin :</small>
                                <div id="previewFin" class="fw-bold">-</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Durée :</small>
                                <div id="previewDuree" class="fw-bold text-primary">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Suggestions de créneaux -->
                    <div class="bg-light rounded p-3 mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-lightbulb me-2"></i>Suggestions rapides
                        </h6>
                        <div class="row" id="suggestions">
                            <!-- Suggestions générées dynamiquement -->
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('periodes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Créer la période
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Aide contextuelle -->
        <div class="col-lg-4">
            <div class="action-card">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-info-circle me-2"></i>Guide de création
                </h6>
                
                <div class="mb-3">
                    <h6>Types de périodes :</h6>
                    <ul class="small">
                        <li><strong>Semestre :</strong> 6 mois, souvent Sep-Jan ou Fév-Juin</li>
                        <li><strong>Trimestre :</strong> 3 mois, découpage en 3 parties</li>
                        <li><strong>Période :</strong> Durée personnalisée selon vos besoins</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6>Bonnes pratiques :</h6>
                    <ul class="small">
                        <li>Une seule période active à la fois</li>
                        <li>Les dates ne doivent pas se chevaucher</li>
                        <li>Prévoir des périodes de vacances entre</li>
                        <li>Nommer clairement (ex: "Semestre 1 2024-2025")</li>
                    </ul>
                </div>

                <div class="alert alert-info">
                    <small>
                        <i class="fas fa-exclamation-circle me-1"></i>
                        <strong>Important :</strong> Les notes ne peuvent être saisies que pendant une période active.
                    </small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anneeSelect = document.querySelector('[name="annee_scolaire_id"]');
    const typeInputs = document.querySelectorAll('[name="type"]');
    const nomInput = document.querySelector('[name="nom"]');
    const debutInput = document.querySelector('[name="debut"]');
    const finInput = document.querySelector('[name="fin"]');
    const preview = document.getElementById('periodePreview');
    const suggestions = document.getElementById('suggestions');

    // Mettre à jour l'aperçu
    function updatePreview() {
        const nom = nomInput.value || '-';
        const type = document.querySelector('[name="type"]:checked')?.value || '-';
        const debut = debutInput.value;
        const fin = finInput.value;
        
        document.getElementById('previewNom').textContent = nom;
        document.getElementById('previewType').textContent = type.charAt(0).toUpperCase() + type.slice(1);
        document.getElementById('previewDebut').textContent = debut ? new Date(debut).toLocaleDateString('fr-FR') : '-';
        document.getElementById('previewFin').textContent = fin ? new Date(fin).toLocaleDateString('fr-FR') : '-';
        
        if (debut && fin) {
            const duree = Math.ceil((new Date(fin) - new Date(debut)) / (1000 * 60 * 60 * 24));
            document.getElementById('previewDuree').textContent = duree + ' jours';
        } else {
            document.getElementById('previewDuree').textContent = '-';
        }
        
        preview.style.display = (nom !== '-' || type !== '-' || debut || fin) ? 'block' : 'none';
    }

    // Générer suggestions selon l'année sélectionnée
    function generateSuggestions() {
        const anneeId = anneeSelect.value;
        if (!anneeId) {
            suggestions.innerHTML = '';
            return;
        }

        // Suggestions basées sur l'année courante
        const year = new Date().getFullYear();
        const suggestionsList = [
            {
                nom: 'Semestre 1',
                type: 'semestre',
                debut: `${year}-09-01`,
                fin: `${year+1}-01-31`
            },
            {
                nom: 'Semestre 2', 
                type: 'semestre',
                debut: `${year+1}-02-01`,
                fin: `${year+1}-06-30`
            },
            {
                nom: 'Trimestre 1',
                type: 'trimestre',
                debut: `${year}-09-01`,
                fin: `${year}-12-15`
            },
            {
                nom: 'Trimestre 2',
                type: 'trimestre', 
                debut: `${year+1}-01-05`,
                fin: `${year+1}-03-30`
            },
            {
                nom: 'Trimestre 3',
                type: 'trimestre',
                debut: `${year+1}-04-01`, 
                fin: `${year+1}-06-30`
            }
        ];

        suggestions.innerHTML = suggestionsList.map(suggestion => `
            <div class="col-md-6 mb-2">
                <button type="button" class="btn btn-outline-primary btn-sm w-100" 
                        onclick="applySuggestion('${suggestion.nom}', '${suggestion.type}', '${suggestion.debut}', '${suggestion.fin}')">
                    <strong>${suggestion.nom}</strong><br>
                    <small>${new Date(suggestion.debut).toLocaleDateString('fr-FR')} - ${new Date(suggestion.fin).toLocaleDateString('fr-FR')}</small>
                </button>
            </div>
        `).join('');
    }

    // Appliquer une suggestion
    window.applySuggestion = function(nom, type, debut, fin) {
        nomInput.value = nom;
        document.querySelector(`[name="type"][value="${type}"]`).checked = true;
        debutInput.value = debut;
        finInput.value = fin;
        updatePreview();
    }

    // Auto-suggestion du nom selon le type
    typeInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (!nomInput.value) {
                const type = this.value;
                const suggestions = {
                    'semestre': 'Semestre 1',
                    'trimestre': 'Trimestre 1', 
                    'periode': 'Période 1'
                };
                nomInput.value = suggestions[type] || '';
            }
            updatePreview();
        });
    });

    // Event listeners
    anneeSelect.addEventListener('change', generateSuggestions);
    nomInput.addEventListener('input', updatePreview);
    debutInput.addEventListener('change', updatePreview);
    finInput.addEventListener('change', updatePreview);

    // Validation des dates
    debutInput.addEventListener('change', function() {
        if (finInput.value && this.value >= finInput.value) {
            alert('La date de début doit être antérieure à la date de fin');
            this.value = '';
        }
    });

    finInput.addEventListener('change', function() {
        if (debutInput.value && this.value <= debutInput.value) {
            alert('La date de fin doit être postérieure à la date de début');
            this.value = '';
        }
    });

    // Initialiser
    generateSuggestions();
    updatePreview();
});
</script>
@endpush