@extends('layouts.app-professeur')

@section('content')
    <div class="container py-5">
        <h1 class="fw-bold text-primary">
            <i class="fas fa-user me-2"></i> Gérer les Filières de {{ $professeur->name }}
        </h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('professeurs.filieres.update', $professeur) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="filieres" class="form-label">Filières</label>
                        <select name="filieres[]" id="filieres" class="form-control" multiple>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ $professeur->filieres->contains($filiere->id) ? 'selected' : '' }}>
                                    {{ $filiere->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection