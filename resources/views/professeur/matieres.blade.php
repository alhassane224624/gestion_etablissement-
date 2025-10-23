@extends('layouts.app-professeur')

@section('content')
    <div class="container py-5">
        <h1 class="fw-bold text-primary">
            <i class="fas fa-book me-2"></i> Gérer les Matières de {{ $professeur->name }}
        </h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('professeurs.matieres.update', $professeur) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="matieres" class="form-label">Matières (séparez par des virgules)</label>
                        <input type="text" name="matieres[]" id="matieres" class="form-control" value="{{ $professeur->matieres->pluck('matiere')->implode(', ') }}" placeholder="Ex: Maths, Physique, Français">
                        <small class="form-text text-muted">Entrez les matières séparées par des virgules.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection