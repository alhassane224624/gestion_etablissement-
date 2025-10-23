@extends('layouts.app')

@section('title', 'Modifier l\'Année Scolaire')

@section('content')
    <h1>Modifier {{ $anneeScolaire->nom }}</h1>
    <form action="{{ route('annees-scolaires.update', $anneeScolaire) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $anneeScolaire->nom) }}" required>
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="debut" class="form-label">Date de début</label>
            <input type="date" name="debut" class="form-control" value="{{ old('debut', $anneeScolaire->debut->format('Y-m-d')) }}" required>
            @error('debut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="fin" class="form-label">Date de fin</label>
            <input type="date" name="fin" class="form-control" value="{{ old('fin', $anneeScolaire->fin->format('Y-m-d')) }}" required>
            @error('fin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ $anneeScolaire->is_active ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Actif</label>
            @error('is_active')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('annees-scolaires.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection