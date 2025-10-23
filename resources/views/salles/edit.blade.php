@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-door-open"></i> Modifier la Salle</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('salles.index') }}">Salles</a></li>
                    <li class="breadcrumb-item active">Modifier {{ $salle->nom }}</li>
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
            <form action="{{ route('salles.update', $salle->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Informations principales</h5>

                        <div class="form-group">
                            <label for="nom">Nom de la salle <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom', $salle->nom) }}" required>
                            @error('nom')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">Sélectionner un type</option>
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}" {{ old('type', $salle->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="capacite">Capacité <span class="text-danger">*</span></label>
                            <input type="number" name="capacite" id="capacite" class="form-control @error('capacite') is-invalid @enderror" value="{{ old('capacite', $salle->capacite) }}" min="1" max="1000" required>
                            @error('capacite')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="batiment">Bâtiment</label>
                            <input type="text" name="batiment" id="batiment" class="form-control @error('batiment') is-invalid @enderror" value="{{ old('batiment', $salle->batiment) }}">
                            @error('batiment')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="etage">Étage</label>
                            <input type="text" name="etage" id="etage" class="form-control @error('etage') is-invalid @enderror" value="{{ old('etage', $salle->etage) }}">
                            @error('etage')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="disponible" name="disponible" value="1" {{ old('disponible', $salle->disponible) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="disponible">Salle disponible</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Équipements</h5>

                        <div class="border p-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach($equipements as $value => $label)
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" id="eq_{{ $value }}" name="equipements[]" value="{{ $value }}" 
                                        {{ in_array($value, old('equipements', $salle->equipements ?? [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="eq_{{ $value }}">
                                        <i class="fas fa-check-circle text-success"></i> {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group mt-3">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $salle->description) }}</textarea>
                            @error('description')
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
                    <a href="{{ route('salles.show', $salle->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection