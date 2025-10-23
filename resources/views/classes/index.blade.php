@extends('layouts.app')

@section('title', 'Gestion des Classes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users-class"></i> Gestion des Classes
        </h1>
        <a href="{{ route('classes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Classe
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

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filtres
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('classes.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Année scolaire</label>
                            <select name="annee_scolaire_id" class="form-control">
                                <option value="">Toutes les années</option>
                                @foreach($annees as $annee)
                                    <option value="{{ $annee->id }}" {{ request('annee_scolaire_id') == $annee->id ? 'selected' : '' }}>
                                        {{ $annee->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Filière</label>
                            <select name="filiere_id" class="form-control">
                                <option value="">Toutes les filières</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Niveau</label>
                            <select name="niveau_id" class="form-control">
                                <option value="">Tous les niveaux</option>
                                @foreach($niveaux as $niveau)
                                    <option value="{{ $niveau->id }}" {{ request('niveau_id') == $niveau->id ? 'selected' : '' }}>
                                        {{ $niveau->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des classes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Liste des Classes ({{ $classes->total() }})
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Nom</th>
                            <th>Niveau</th>
                            <th>Filière</th>
                            <th>Année Scolaire</th>
                            <th>Effectif</th>
                            <th>Taux Remplissage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $classe)
                            <tr>
                                <td>
                                    <strong>{{ $classe->nom }}</strong>
                                </td>
                                <td>{{ $classe->niveau->nom }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $classe->filiere->nom }}
                                    </span>
                                </td>
                                <td>{{ $classe->anneeScolaire->nom }}</td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $classe->effectif_actuel }} / {{ $classe->effectif_max }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $classe->taux_remplissage >= 90 ? 'bg-danger' : ($classe->taux_remplissage >= 70 ? 'bg-warning' : 'bg-success') }}" 
                                             role="progressbar" 
                                             style="width: {{ $classe->taux_remplissage }}%"
                                             aria-valuenow="{{ $classe->taux_remplissage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ number_format($classe->taux_remplissage, 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('classes.show', $classe) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('classes.edit', $classe) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="deleteClasse({{ $classe->id }})"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Aucune classe trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $classes->links() }}
            </div>
        </div>
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
function deleteClasse(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')) {
        const form = document.getElementById('delete-form');
        form.action = `/classes/${id}`;
        form.submit();
    }
}
</script>
@endpush