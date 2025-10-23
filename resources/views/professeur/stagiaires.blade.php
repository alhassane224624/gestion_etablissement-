@extends('layouts.app-professeur')

@section('content')
    <div class="container py-5">
        <h1 class="fw-bold text-primary">
            <i class="fas fa-users me-2"></i> Liste des Stagiaires
        </h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('professeur.stagiaires') }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Rechercher par nom, prénom ou matricule" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <select name="filiere_id" class="form-control">
                                <option value="">Toutes les filières</option>
                                @foreach ($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Filière</th>
                            <th>Actes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stagiaires as $stagiaire)
                            <tr>
                                <td>{{ $stagiaire->matricule }}</td>
                                <td>{{ $stagiaire->nom }}</td>
                                <td>{{ $stagiaire->prenom }}</td>
                                <td>{{ $stagiaire->filiere->nom ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('professeur.stagiaires.notes', $stagiaire) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye me-1"></i> Voir les notes
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-exclamation-circle me-2"></i> Aucun stagiaire trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $stagiaires->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection