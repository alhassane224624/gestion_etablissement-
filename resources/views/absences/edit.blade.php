@extends('layouts.app')

@section('title', 'Modifier Absence')
@section('page-title', 'Modifier l\'Absence')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="action-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2 text-warning"></i>
                        Modifier l'absence du {{ $absence->date->format('d/m/Y') }}
                    </h5>
                    <span class="badge bg-info">
                        {{ $absence->stagiaire->nom }} {{ $absence->stagiaire->prenom }}
                    </span>
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

                <form method="POST" action="{{ route('absences.update', $absence) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Informations de base -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Stagiaire</label>
                            <div class="form-control-plaintext bg-light rounded p-2">
                                <div class="d-flex align-items-center">
                                    @if($absence->stagiaire->photo)
                                        <img src="{{ asset('storage/' . $absence->stagiaire->photo) }}" 
                                             class="rounded-circle me-2" width="30" height="30" alt="Photo">
                                    @else
                                        <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                             style="width: 30px; height: 30px;">
                                            <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $absence->stagiaire->nom }} {{ $absence->stagiaire->prenom }}</strong><br>
                                        <small class="text-muted">{{ $absence->stagiaire->matricule }} - {{ $absence->stagiaire->filiere->nom }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date *</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', $absence->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Type d'absence -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Type d'absence *</label>
                            <select name="type" id="typeAbsence" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Sélectionner le type</option>
                                <option value="matin" {{ old('type', $absence->type) == 'matin' ? 'selected' : '' }}>Matin (4h)</option>
                                <option value="apres_midi" {{ old('type', $absence->type) == 'apres_midi' ? 'selected' : '' }}>Après-midi (4h)</option>
                                <option value="journee" {{ old('type', $absence->type) == 'journee' ? 'selected' : '' }}>Journée complète (8h)</option>
                                <option value="heure" {{ old('type', $absence->type) == 'heure' ? 'selected' : '' }}>Par heure</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4" id="heureDebutDiv" style="display: {{ $absence->type == 'heure' ? 'block' : 'none' }};">
                            <label class="form-label fw-bold">Heure début</label>
                            <input type="time" name="heure_debut" class="form-control @error('heure_debut') is-invalid @enderror" 
                                   value="{{ old('heure_debut', $absence->heure_debut) }}">
                            @error('heure_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4" id="heureFinDiv" style="display: {{ $absence->type == 'heure' ? 'block' : 'none' }};">
                            <label class="form-label fw-bold">Heure fin</label>
                            <input type="time" name="heure_fin" class="form-control @error('heure_fin') is-invalid @enderror" 
                                   value="{{ old('heure_fin', $absence->heure_fin) }}">
                            @error('heure_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Justification -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="justifiee" id="justifieeCheck" 
                                       {{ old('justifiee', $absence->justifiee) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="justifieeCheck">
                                    Absence justifiée
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6" id="documentDiv" style="display: {{ $absence->justifiee ? 'block' : 'none' }};">
                            <label class="form-label">Document justificatif</label>
                            @if($absence->document_justificatif)
                                <div class="mb-2">
                                    <small class="text-muted">Document actuel :</small>
                                    <a href="{{ asset('storage/' . $absence->document_justificatif) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-info ms-2">
                                        <i class="fas fa-file-pdf me-1"></i>Voir le document
                                    </a>
                                </div>
                            @endif
                            <input type="file" name="document_justificatif" 
                                   class="form-control @error('document_justificatif') is-invalid @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Formats acceptés: PDF, JPG, PNG (max: 2MB)</small>
                            @error('document_justificatif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Motif -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Motif (optionnel)</label>
                        <textarea name="motif" class="form-control @error('motif') is-invalid @enderror" 
                                  rows="3" placeholder="Précisez le motif de l'absence...">{{ old('motif', $absence->motif) }}</textarea>
                        @error('motif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Informations d'audit -->
                    <div class="bg-light rounded p-3 mb-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Créé par :</strong> {{ $absence->creator->name ?? 'Système' }} 
                            le {{ $absence->created_at->format('d/m/Y à H:i') }}
                            @if($absence->updated_at != $absence->created_at)
                                <br><strong>Dernière modification :</strong> {{ $absence->updated_at->format('d/m/Y à H:i') }}
                            @endif
                        </small>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('absences.show', $absence) }}" class="btn btn-info">
                                <i class="fas fa-eye me-2"></i>Voir
                            </a>
                            <a href="{{ route('absences.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list me-2"></i>Liste
                            </a>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Sauvegarder les modifications
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
    const typeSelect = document.getElementById('typeAbsence');
    const heureDebutDiv = document.getElementById('heureDebutDiv');
    const heureFinDiv = document.getElementById('heureFinDiv');
    const justifieeCheck = document.getElementById('justifieeCheck');
    const documentDiv = document.getElementById('documentDiv');

    // Gestion de l'affichage des heures selon le type
    typeSelect.addEventListener('change', function() {
        if (this.value === 'heure') {
            heureDebutDiv.style.display = 'block';
            heureFinDiv.style.display = 'block';
            document.querySelector('input[name="heure_debut"]').required = true;
            document.querySelector('input[name="heure_fin"]').required = true;
        } else {
            heureDebutDiv.style.display = 'none';
            heureFinDiv.style.display = 'none';
            document.querySelector('input[name="heure_debut"]').required = false;
            document.querySelector('input[name="heure_fin"]').required = false;
        }
    });

    // Gestion de l'affichage du document justificatif
    justifieeCheck.addEventListener('change', function() {
        documentDiv.style.display = this.checked ? 'block' : 'none';
    });
});
</script>
@endpush