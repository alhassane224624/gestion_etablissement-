@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-calendar-edit"></i> Modifier le Cours</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('planning.index') }}">Planning</a></li>
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
            <form action="{{ route('planning.update', $planning) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations du cours</h5>

                        <div class="form-group">
                            <label for="date">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $planning->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="heure_debut">Heure début <span class="text-danger">*</span></label>
                                    <input type="time" name="heure_debut" id="heure_debut" class="form-control @error('heure_debut') is-invalid @enderror" value="{{ old('heure_debut', $planning->heure_debut) }}" required>
                                    @error('heure_debut')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="heure_fin">Heure fin <span class="text-danger">*</span></label>
                                    <input type="time" name="heure_fin" id="heure_fin" class="form-control @error('heure_fin') is-invalid @enderror" value="{{ old('heure_fin', $planning->heure_fin) }}" required>
                                    @error('heure_fin')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="matiere_id">Matière <span class="text-danger">*</span></label>
                            <select name="matiere_id" id="matiere_id" class="form-control @error('matiere_id') is-invalid @enderror" required>
                                <option value="">Sélectionner une matière</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ old('matiere_id', $planning->matiere_id) == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }} ({{ $matiere->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('matiere_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type_cours">Type de cours <span class="text-danger">*</span></label>
                            <select name="type_cours" id="type_cours" class="form-control @error('type_cours') is-invalid @enderror" required>
                                <option value="cours" {{ old('type_cours', $planning->type_cours) == 'cours' ? 'selected' : '' }}>Cours magistral</option>
                                <option value="td" {{ old('type_cours', $planning->type_cours) == 'td' ? 'selected' : '' }}>Travaux dirigés</option>
                                <option value="tp" {{ old('type_cours', $planning->type_cours) == 'tp' ? 'selected' : '' }}>Travaux pratiques</option>
                                <option value="examen" {{ old('type_cours', $planning->type_cours) == 'examen' ? 'selected' : '' }}>Examen</option>
                            </select>
                            @error('type_cours')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $planning->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Affectations</h5>

                        <div class="form-group">
                            <label for="classe_id">Classe <span class="text-danger">*</span></label>
                            <select name="classe_id" id="classe_id" class="form-control @error('classe_id') is-invalid @enderror" required>
                                <option value="">Sélectionner une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id', $planning->classe_id) == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }} - {{ $classe->niveau->nom }} ({{ $classe->filiere->nom }})
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="professeur_id">Professeur <span class="text-danger">*</span></label>
                            <select name="professeur_id" id="professeur_id" class="form-control @error('professeur_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un professeur</option>
                                @foreach($professeurs as $professeur)
                                    <option value="{{ $professeur->id }}" {{ old('professeur_id', $planning->professeur_id) == $professeur->id ? 'selected' : '' }}>
                                        {{ $professeur->name }}
                                        @if($professeur->specialite)
                                            ({{ $professeur->specialite }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('professeur_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="salle_id">Salle <span class="text-danger">*</span></label>
                            <select name="salle_id" id="salle_id" class="form-control @error('salle_id') is-invalid @enderror" required>
                                <option value="">Sélectionner une salle</option>
                                @foreach($salles as $salle)
                                    <option value="{{ $salle->id }}" {{ old('salle_id', $planning->salle_id) == $salle->id ? 'selected' : '' }}>
                                        {{ $salle->nom }} - {{ $salle->type_libelle }} ({{ $salle->capacite }} places)
                                    </option>
                                @endforeach
                            </select>
                            @error('salle_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted" id="disponibilite-info"></small>
                        </div>

                        <div class="form-group">
                            <label for="statut">Statut <span class="text-danger">*</span></label>
                            <select name="statut" id="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                <option value="brouillon" {{ old('statut', $planning->statut) == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="valide" {{ old('statut', $planning->statut) == 'valide' ? 'selected' : '' }}>Validé</option>
                                <option value="en_cours" {{ old('statut', $planning->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="termine" {{ old('statut', $planning->statut) == 'termine' ? 'selected' : '' }}>Terminé</option>
                                <option value="annule" {{ old('statut', $planning->statut) == 'annule' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('statut')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="motif-annulation-group" style="display: none;">
                            <label for="motif_annulation">Motif d'annulation</label>
                            <textarea name="motif_annulation" id="motif_annulation" rows="3" class="form-control @error('motif_annulation') is-invalid @enderror">{{ old('motif_annulation', $planning->motif_annulation) }}</textarea>
                            @error('motif_annulation')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                    <a href="{{ route('planning.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Afficher/masquer le champ motif d'annulation
document.getElementById('statut').addEventListener('change', function() {
    const motifGroup = document.getElementById('motif-annulation-group');
    if(this.value === 'annule') {
        motifGroup.style.display = 'block';
    } else {
        motifGroup.style.display = 'none';
    }
});

// Vérifier au chargement
if(document.getElementById('statut').value === 'annule') {
    document.getElementById('motif-annulation-group').style.display = 'block';
}

// Vérifier la disponibilité de la salle
function checkDisponibilite() {
    const salleId = document.getElementById('salle_id').value;
    const date = document.getElementById('date').value;
    const heureDebut = document.getElementById('heure_debut').value;
    const heureFin = document.getElementById('heure_fin').value;
    
    if(salleId && date && heureDebut && heureFin) {
        fetch('/salles/check-disponibilite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                salle_id: salleId,
                date: date,
                heure_debut: heureDebut,
                heure_fin: heureFin,
                exclude_planning_id: {{ $planning->id }}
            })
        })
        .then(response => response.json())
        .then(data => {
            const infoElement = document.getElementById('disponibilite-info');
            if(data.disponible) {
                infoElement.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Salle disponible</span>';
            } else {
                infoElement.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> ' + data.message + '</span>';
            }
        });
    }
}

document.getElementById('salle_id').addEventListener('change', checkDisponibilite);
document.getElementById('date').addEventListener('change', checkDisponibilite);
document.getElementById('heure_debut').addEventListener('change', checkDisponibilite);
document.getElementById('heure_fin').addEventListener('change', checkDisponibilite);
</script>
@endsection