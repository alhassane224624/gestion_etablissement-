@extends('layouts.app')

@section('title', 'Modifier une Période')

@section('content')
    <h1>Modifier la période : {{ $periode->nom }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('periodes.update', $periode->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="annee_scolaire_id" class="form-label">Année Scolaire</label>
            <select name="annee_scolaire_id" id="annee_scolaire_id" class="form-control" required>
                <option value="">-- Sélectionner une année --</option>
                @foreach($annees as $annee)
                    <option value="{{ $annee->id }}" {{ old('annee_scolaire_id', $periode->annee_scolaire_id) == $annee->id ? 'selected' : '' }}>
                        {{ $annee->nom }}
                    </option>
                @endforeach
            </select>
            @error('annee_scolaire_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la période</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $periode->nom) }}" required>
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">-- Sélectionner un type --</option>
                <option value="semestre" {{ old('type', $periode->type) == 'semestre' ? 'selected' : '' }}>Semestre</option>
                <option value="trimestre" {{ old('type', $periode->type) == 'trimestre' ? 'selected' : '' }}>Trimestre</option>
                <option value="periode" {{ old('type', $periode->type) == 'periode' ? 'selected' : '' }}>Période</option>
            </select>
            @error('type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="debut" class="form-label">Date de début</label>
            <input type="date" name="debut" id="debut" class="form-control" value="{{ old('debut', $periode->debut->format('Y-m-d')) }}" required>
            @error('debut')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="fin" class="form-label">Date de fin</label>
            <input type="date" name="fin" id="fin" class="form-control" value="{{ old('fin', $periode->fin->format('Y-m-d')) }}" required>
            @error('fin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $periode->is_active) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Activer cette période</label>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('periodes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection