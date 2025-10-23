@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-user-edit"></i> Modifier l'Utilisateur</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
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
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations de base</h5>
                        
                        <div class="form-group">
                            <label for="name">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Nouveau mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="role">Rôle <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="stagiaire" {{ old('role', $user->role) == 'stagiaire' ? 'selected' : '' }}>Stagiaire</option>
                                <option value="professeur" {{ old('role', $user->role) == 'professeur' ? 'selected' : '' }}>Professeur</option>
                                <option value="administrateur" {{ old('role', $user->role) == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="text" name="telephone" id="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone', $user->telephone) }}">
                            @error('telephone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Compte actif</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Informations complémentaires</h5>

                        <div class="form-group">
                            <label for="specialite">Spécialité</label>
                            <input type="text" name="specialite" id="specialite" class="form-control @error('specialite') is-invalid @enderror" value="{{ old('specialite', $user->specialite) }}">
                            @error('specialite')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bio">Biographie</label>
                            <textarea name="bio" id="bio" rows="4" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="professeur-section" style="display: none;">
                            <hr>
                            <h5 class="mb-3">Attribution Professeur</h5>

                            <div class="form-group">
                                <label>Filières</label>
                                <div class="border p-3" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($filieres as $filiere)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="filiere_{{ $filiere->id }}" name="filieres[]" value="{{ $filiere->id }}" 
                                            {{ in_array($filiere->id, old('filieres', $user->filieres->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="filiere_{{ $filiere->id }}">
                                                {{ $filiere->nom }} ({{ $filiere->niveau }})
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
                                            <input type="checkbox" class="custom-control-input" id="matiere_{{ $matiere->id }}" name="matieres[]" value="{{ $matiere->id }}" 
                                            {{ in_array($matiere->id, old('matieres', $user->matieresEnseignees->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="matiere_{{ $matiere->id }}">
                                                {{ $matiere->nom }} ({{ $matiere->code }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
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
    if(this.value === 'professeur') {
        professeurSection.style.display = 'block';
    } else {
        professeurSection.style.display = 'none';
    }
});

if(document.getElementById('role').value === 'professeur') {
    document.getElementById('professeur-section').style.display = 'block';
}
</script>
@endsection