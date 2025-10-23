@extends('layouts.app')

@section('title', 'Ajouter une Année Scolaire')

@section('content')
    <h1>Ajouter une Année Scolaire</h1>
    <form action="{{ route('annees-scolaires.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom') }}" required>
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="debut" class="form-label">Date de début</label>
            <input type="date" name="debut" class="form-control" value="{{ old('debut') }}" required>
            @error('debut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="fin" class="form-label">Date de fin</label>
            <input type="date" name="fin" class="form-control" value="{{ old('fin') }}" required>
            @error('fin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" class="form-check-input" value="1">
            <label for="is_active" class="form-check-label">Actif</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="create_default_periods" class="form-check-input" value="1">
            <label for="create_default_periods" class="form-check-label">Créer des périodes par défaut</label>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
        <a href="{{ route('annees-scolaires.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection