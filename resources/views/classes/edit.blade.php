@extends('layouts.app')

@section('title', 'Modifier la Classe')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit"></i> Modifier la Classe: {{ $classe->nom }}
        </h1>
        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
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

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Informations de la Classe
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('classes.update', $classe) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nom">Nom de la classe <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom', $classe->nom) }}"
                                   required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filiere_id">Filière <span class="text-danger">*</span></label>
                                    <select class="form-control @error('filiere_id') is-invalid @enderror" 
                                            id="filiere_id" 
                                            name="filiere_id"
                                            required>
                                        <option value="">Sélectionner une filière</option>
                                        @foreach($filieres as $filiere)
                                            <option value="{{ $filiere->id }}" 
                                                    {{ old('filiere_id', $classe->filiere_id) == $filiere->id ? 'selected' : '' }}>
                                                {{ $filiere->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('filiere_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="niveau_id">Niveau <span class="text-danger">*</span></label>
                                    <select class="form-control @error('niveau_id') is-invalid @enderror" 
                                            id="niveau_id" 
                                            name="niveau_id"
                                            required>
                                        <option value="">Sélectionner un niveau</option>
                                        @foreach($niveaux as $niveau)
                                            <option value="{{ $niveau->id }}" 
                                                    data-filiere="{{ $niveau->filiere_id }}"
                                                    {{ old('niveau_id', $classe->niveau_id) == $niveau->id ? 'selected' : '' }}>
                                                {{ $niveau->nom }} - {{ $niveau->filiere->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('niveau_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="annee_scolaire_id">Année Scolaire <span class="text-danger">*</span></label>
                                    <select class="form-control @error('annee_scolaire_id') is-invalid @enderror" 
                                            id="annee_scolaire_id" 
                                            name="annee_scolaire_id"
                                            required>
                                        <option value="">Sélectionner une année</option>
                                        @foreach($annees as $annee)
                                            <option value="{{ $annee->id }}" 
                                                    {{ old('annee_scolaire_id', $classe->annee_scolaire_id) == $annee->id ? 'selected' : '' }}>
                                                {{ $annee->nom }}
                                                @if($annee->is_active)
                                                    (Active)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('annee_scolaire_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="effectif_max">Effectif Maximum <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('effectif_max') is-invalid @enderror" 
                                           id="effectif_max" 
                                           name="effectif_max" 
                                           value="{{ old('effectif_max', $classe->effectif_max) }}"
                                           min="{{ $classe->effectif_actuel }}"
                                           max="100"
                                           required>
                                    @error('effectif_max')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Minimum: {{ $classe->effectif_actuel }} (effectif actuel)
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Effectif actuel:</strong> {{ $classe->effectif_actuel }} stagiaire(s)
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer les Modifications
                            </button>
                        </div>
                    </form>
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
                        <label class="small text-muted">Effectif</label>
                        <h5>{{ $classe->effectif_actuel }} / {{ $classe->effectif_max }}</h5>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar {{ $classe->taux_remplissage >= 90 ? 'bg-danger' : ($classe->taux_remplissage >= 70 ? 'bg-warning' : 'bg-success') }}" 
                                 style="width: {{ $classe->taux_remplissage }}%">
                                {{ number_format($classe->taux_remplissage, 1) }}%
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="small text-muted">Places Disponibles</label>
                        <h5>{{ $classe->places_disponibles }}</h5>
                    </div>

                    <hr>

                    <div class="mb-0">
                        <label class="small text-muted">Statut</label>
                        <h5>
                            @if($classe->is_full)
                                <span class="badge badge-danger">Complète</span>
                            @else
                                <span class="badge badge-success">Places Disponibles</span>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Attention
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small mb-0">
                        L'effectif maximum ne peut pas être inférieur à l'effectif actuel ({{ $classe->effectif_actuel }} stagiaire(s)).
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#filiere_id').on('change', function() {
        const filiereId = $(this).val();
        const $niveauSelect = $('#niveau_id');
        
        $niveauSelect.find('option').each(function() {
            const $option = $(this);
            if ($option.val() === '') {
                $option.show();
                return;
            }
            
            if ($option.data('filiere') == filiereId) {
                $option.show();
            } else {
                $option.hide();
            }
        });
        
        if ($niveauSelect.find('option:selected').is(':hidden')) {
            $niveauSelect.val('');
        }
    });
    
    if ($('#filiere_id').val()) {
        $('#filiere_id').trigger('change');
    }
});
</script>
@endpush
