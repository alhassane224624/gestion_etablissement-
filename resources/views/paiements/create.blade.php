@extends('layouts.app')

@section('title', 'Enregistrer un Paiement')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-cash-register me-2"></i> Enregistrer un Paiement</h2>
        <a href="{{ route('paiements.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <form method="POST" action="{{ route('paiements.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            {{-- Colonne gauche --}}
            <div class="col-md-8">
                {{-- Informations du stagiaire --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-graduate me-2"></i>Stagiaire
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Sélectionner un stagiaire <span class="text-danger">*</span></label>
                            <select name="stagiaire_id" id="stagiaireSelect" class="form-select @error('stagiaire_id') is-invalid @enderror" required>
                                <option value="">-- Choisir un stagiaire --</option>
                                @foreach($stagiaires as $s)
                                <option value="{{ $s->id }}" 
                                        data-filiere="{{ $s->filiere->nom ?? 'N/A' }}"
                                        data-matricule="{{ $s->matricule }}"
                                        data-solde="{{ $s->solde_restant }}"
                                        {{ old('stagiaire_id', $stagiaire->id ?? '') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nom_complet }} - {{ $s->matricule }}
                                </option>
                                @endforeach
                            </select>
                            @error('stagiaire_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Informations du stagiaire sélectionné --}}
                        <div id="stagiaireInfo" class="alert alert-info" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Filière :</strong>
                                    <p class="mb-0" id="infoFiliere">-</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Matricule :</strong>
                                    <p class="mb-0" id="infoMatricule">-</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Solde restant :</strong>
                                    <p class="mb-0 text-danger fw-bold" id="infoSolde">-</p>
                                </div>
                            </div>
                        </div>

                        @if($stagiaire)
                        {{-- Échéanciers impayés --}}
                        <div class="mt-3">
                            <h6 class="mb-3">Échéanciers à payer</h6>
                            @if($stagiaire->echeanciersImpayes->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th width="30"></th>
                                                <th>Titre</th>
                                                <th>Échéance</th>
                                                <th>Montant</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stagiaire->echeanciersImpayes as $ech)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="echeanciers[]" value="{{ $ech->id }}" class="form-check-input">
                                                </td>
                                                <td>{{ $ech->titre }}</td>
                                                <td>{{ $ech->date_echeance->format('d/m/Y') }}</td>
                                                <td>{{ number_format($ech->montant_restant, 2) }} DH</td>
                                                <td>
                                                    <span class="badge bg-{{ $ech->is_en_retard ? 'danger' : 'warning' }}">
                                                        {{ $ech->statut_libelle }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Si aucun échéancier n'est sélectionné, le paiement sera affecté automatiquement aux plus anciens.
                                </small>
                            @else
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>Aucun échéancier impayé
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Détails du paiement --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>Détails du Paiement
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Montant (DH) <span class="text-danger">*</span></label>
                                <input type="number" name="montant" class="form-control @error('montant') is-invalid @enderror" 
                                       step="0.01" min="1" value="{{ old('montant') }}" required>
                                @error('montant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date du paiement <span class="text-danger">*</span></label>
                                <input type="date" name="date_paiement" class="form-control @error('date_paiement') is-invalid @enderror" 
                                       value="{{ old('date_paiement', now()->format('Y-m-d')) }}" required>
                                @error('date_paiement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type de paiement <span class="text-danger">*</span></label>
                                <select name="type_paiement" class="form-select @error('type_paiement') is-invalid @enderror" required>
                                    <option value="">-- Choisir --</option>
                                    <option value="inscription" {{ old('type_paiement') == 'inscription' ? 'selected' : '' }}>Frais d'inscription</option>
                                    <option value="mensualite" {{ old('type_paiement') == 'mensualite' ? 'selected' : '' }}>Mensualité</option>
                                    <option value="examen" {{ old('type_paiement') == 'examen' ? 'selected' : '' }}>Frais d'examen</option>
                                    <option value="autre" {{ old('type_paiement') == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('type_paiement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Méthode de paiement <span class="text-danger">*</span></label>
                                <select name="methode_paiement" class="form-select @error('methode_paiement') is-invalid @enderror" required>
                                    <option value="">-- Choisir --</option>
                                    <option value="especes" {{ old('methode_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                                    <option value="virement" {{ old('methode_paiement') == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                                    <option value="cheque" {{ old('methode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                                    <option value="carte" {{ old('methode_paiement') == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                                    <option value="mobile_money" {{ old('methode_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                </select>
                                @error('methode_paiement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="2" placeholder="Ex: Paiement mensualité Janvier 2024">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Justificatif (optionnel)</label>
                                <input type="file" name="justificatif" class="form-control @error('justificatif') is-invalid @enderror" 
                                       accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Format acceptés : PDF, JPG, PNG (Max: 5 Mo)</small>
                                @error('justificatif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Colonne droite --}}
            <div class="col-md-4">
                {{-- Notes administratives --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-sticky-note me-2"></i>Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Notes administratives</label>
                            <textarea name="notes_admin" class="form-control" rows="4" 
                                      placeholder="Notes internes (non visibles par le stagiaire)">{{ old('notes_admin') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Informations importantes --}}
                <div class="card border-0 shadow-sm mb-4 border-start border-info border-4">
                    <div class="card-body">
                        <h6 class="text-info">
                            <i class="fas fa-info-circle me-2"></i>Informations
                        </h6>
                        <ul class="small mb-0">
                            <li class="mb-2">Les paiements en <strong>espèces</strong> sont validés automatiquement</li>
                            <li class="mb-2">Les autres méthodes nécessitent une validation manuelle</li>
                            <li class="mb-2">Un reçu sera généré automatiquement après validation</li>
                            <li>Le stagiaire recevra une notification</li>
                        </ul>
                    </div>
                </div>

                {{-- Boutons d'action --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-save me-2"></i>Enregistrer le paiement
                        </button>
                        <a href="{{ route('paiements.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stagiaireSelect = document.getElementById('stagiaireSelect');
    const stagiaireInfo = document.getElementById('stagiaireInfo');
    
    stagiaireSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        
        if (this.value) {
            document.getElementById('infoFiliere').textContent = option.dataset.filiere;
            document.getElementById('infoMatricule').textContent = option.dataset.matricule;
            document.getElementById('infoSolde').textContent = parseFloat(option.dataset.solde).toFixed(2) + ' DH';
            stagiaireInfo.style.display = 'block';
        } else {
            stagiaireInfo.style.display = 'none';
        }
    });
    
    // Trigger au chargement si un stagiaire est pré-sélectionné
    if (stagiaireSelect.value) {
        stagiaireSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection