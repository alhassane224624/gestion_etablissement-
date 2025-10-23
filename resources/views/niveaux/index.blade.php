@extends('layouts.app')

@section('title', 'Gestion des Niveaux')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-layer-group"></i> Gestion des Niveaux
        </h1>
        <a href="{{ route('niveaux.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Niveau
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

    <!-- Liste des niveaux regroupés par filière -->
    @foreach($niveaux->groupBy('filiere_id') as $filiereId => $niveauxFiliere)
        @php
            $filiere = $niveauxFiliere->first()->filiere;
        @endphp
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-gradient-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-graduation-cap"></i> {{ $filiere->nom }} - {{ $filiere->niveau }}
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">Ordre</th>
                                <th width="25%">Nom du Niveau</th>
                                <th width="15%">Durée</th>
                                <th width="15%">Classes</th>
                                <th width="15%">Stagiaires</th>
                                <th width="15%">Matières</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($niveauxFiliere as $niveau)
                                <tr>
                                    <td>
                                        <span class="badge badge-primary badge-lg">
                                            {{ $niveau->ordre }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $niveau->nom }}</strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar"></i> {{ $niveau->duree_semestres }} semestre(s)
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $niveau->classes_count }} classe(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ $niveau->stagiaires_count }} stagiaire(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">
                                            {{ $niveau->matieres->count() }} matière(s)
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('niveaux.show', $niveau) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('niveaux.edit', $niveau) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="deleteNiveau({{ $niveau->id }})"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    @if($niveaux->isEmpty())
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-layer-group fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucun niveau trouvé</h4>
                <p class="text-muted">Commencez par créer votre premier niveau</p>
                <a href="{{ route('niveaux.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Créer un Niveau
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Delete Form -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function deleteNiveau(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce niveau ?\n\nCette action est irréversible et supprimera toutes les données associées.')) {
        const form = document.getElementById('delete-form');
        form.action = `/niveaux/${id}`;
        form.submit();
    }
}
</script>
@endpush