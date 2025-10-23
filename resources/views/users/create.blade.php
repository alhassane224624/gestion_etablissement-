@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-user-plus"></i> Créer un Utilisateur</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
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
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Informations de base -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations de base</h5>

                        <div class="form-group">
                            <label for="name">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="role">Rôle <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="">Sélectionner un rôle</option>
                                <option value="stagiaire" {{ old('role') == 'stagiaire' ? 'selected' : '' }}>Stagiaire</option>
                                <option value="professeur" {{ old('role') == 'professeur' ? 'selected' : '' }}>Professeur</option>
                                <option value="administrateur" {{ old('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="text" name="telephone" id="telephone" class="form-control" value="{{ old('telephone') }}">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Compte actif</label>
                            </div>
                        </div>
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations complémentaires</h5>

                        <div class="form-group">
                            <label for="specialite">Spécialité</label>
                            <input type="text" name="specialite" id="specialite" class="form-control" value="{{ old('specialite') }}">
                        </div>

                        <div class="form-group">
                            <label for="bio">Biographie</label>
                            <textarea name="bio" id="bio" rows="3" class="form-control">{{ old('bio') }}</textarea>
                        </div>

                        <!-- Section Professeur -->
                        <div id="professeur-section" style="display: none;">
                            <hr>
                            <h5 class="mb-3">Attribution Professeur</h5>

                            <div class="form-group">
                                <label>Filières</label>
                                <div class="border p-3" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($filieres as $filiere)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="filiere_{{ $filiere->id }}" name="filieres[]" value="{{ $filiere->id }}">
                                            <label class="custom-control-label" for="filiere_{{ $filiere->id }}">
                                                {{ $filiere->nom }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Matières</label>
                                <div class="border p-3" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($matieres as $matiere)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="matiere_{{ $matiere->id }}" name="matieres[]" value="{{ $matiere->id }}">
                                            <label class="custom-control-label" for="matiere_{{ $matiere->id }}">
                                                {{ $matiere->nom }} ({{ $matiere->code }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Section Stagiaire -->
                        <div id="stagiaire-section" style="display: none;">
                            <hr>
                            <h5 class="mb-3">Informations Stagiaire</h5>

                            <div class="form-group">
                                <label for="filiere_id">Filière</label>
                                <select name="filiere_id" id="filiere_id" class="form-control">
                                    <option value="">-- Sélectionner une filière --</option>
                                    @foreach($filieres as $filiere)
                                        <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="classe_id">Classe</label>
                                <select name="classe_id" id="classe_id" class="form-control">
                                    <option value="">-- Sélectionner une classe --</option>
                                    @foreach(\App\Models\Classe::with('filiere')->get() as $classe)
                                        <option value="{{ $classe->id }}">
                                            {{ $classe->nom }} - {{ $classe->filiere->nom ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="niveau_id">Niveau</label>
                                <select name="niveau_id" id="niveau_id" class="form-control">
                                    <option value="">-- Sélectionner un niveau --</option>
                                    @foreach(\App\Models\Niveau::all() as $niveau)
                                        <option value="{{ $niveau->id }}">{{ $niveau->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <p class="text-muted mt-2">
                                💡 Le stagiaire aura automatiquement un matricule et un compte utilisateur associé.
                            </p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const professeurSection = document.getElementById('professeur-section');
    const stagiaireSection = document.getElementById('stagiaire-section');

    professeurSection.style.display = (this.value === 'professeur') ? 'block' : 'none';
    stagiaireSection.style.display = (this.value === 'stagiaire') ? 'block' : 'none';
});

// Affiche correctement la section au chargement (utile lors d'une erreur de validation)
window.addEventListener('DOMContentLoaded', () => {
    const role = document.getElementById('role').value;
    if (role === 'professeur') document.getElementById('professeur-section').style.display = 'block';
    if (role === 'stagiaire') document.getElementById('stagiaire-section').style.display = 'block';
});
</script>
@endsection
