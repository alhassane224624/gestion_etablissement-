@extends('layouts.app')

@section('title', 'Gestion des Filières')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-graduation-cap"></i> Gestion des Filières
        </h1>
        <a href="{{ route('filieres.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Filière
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Barre de recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-search"></i> Recherche
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('filieres.index') }}">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="Rechercher une filière par nom ou niveau..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des filières -->
    <div class="row">
        @forelse($filieres as $filiere)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-gradient-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-graduation-cap"></i> {{ $filiere->nom }}
                            </h5>
                            <span class="badge badge-light">
                                {{ $filiere->niveau }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">
                                    <i class="fas fa-users"></i> Stagiaires actifs
                                </span>
                                <span class="font-weight-bold text-primary">
                                    {{ $filiere->getTotalStagiairesActifs() }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">
                                    <i class="fas fa-book"></i> Matières
                                </span>
                                <span class="font-weight-bold text-info">
                                    {{ $filiere->matieres->count() }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">
                                    <i class="fas fa-chalkboard-teacher"></i> Professeurs
                                </span>
                                <span class="font-weight-bold text-success">
                                    {{ $filiere->professeurs->count() }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">
                                    <i class="fas fa-layer-group"></i> Niveaux
                                </span>
                                <span class="font-weight-bold text-warning">
                                    {{ $filiere->niveaux->count() }}
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="text-muted small mb-3">
                            <strong>Nom complet:</strong><br>
                            {{ $filiere->nom_complet }}
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('filieres.show', $filiere) }}" 
                               class="btn btn-sm btn-info"
                               title="Voir">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            <a href="{{ route('filieres.edit', $filiere) }}" 
                               class="btn btn-sm btn-warning"
                               title="Modifier">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="deleteFiliere({{ $filiere->id }})"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-graduation-cap fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucune filière trouvée</h4>
                        <p class="text-muted">Commencez par créer votre première filière</p>
                        <a href="{{ route('filieres.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Créer une Filière
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function deleteFiliere(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette filière ?\n\nCette action supprimera également toutes les données associées (niveaux, classes, etc.).')) {
        const form = document.getElementById('delete-form');
        form.action = `/filieres/${id}`;
        form.submit();
    }
}
</script>
@endpush