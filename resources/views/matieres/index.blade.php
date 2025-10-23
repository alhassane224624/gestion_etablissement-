@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-book"></i> Gestion des Matières</h2>
                <a href="{{ route('matieres.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle Matière
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Matières</h6>
                    <h3>{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Avec Filières</h6>
                    <h3>{{ $stats['matieres_avec_filieres'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Avec Niveaux</h6>
                    <h3>{{ $stats['matieres_avec_niveaux'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('matieres.index') }}">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par nom ou code..." value="{{ request('search') }}">
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Liste des matières -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Coefficient</th>
                            <th>Couleur</th>
                            <th>Filières</th>
                            <th>Niveaux</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matieres as $matiere)
                            <tr>
                                <td><strong>{{ $matiere->code }}</strong></td>
                                <td>{{ $matiere->nom }}</td>
                                <td>
                                    <span class="badge badge-primary">Coef. {{ $matiere->coefficient }}</span>
                                </td>
                                <td>
                                    @if($matiere->couleur)
                                        <span class="badge" style="background-color: {{ $matiere->couleur }}; color: white;">
                                            {{ $matiere->couleur }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($matiere->filieres->count() > 0)
                                        <span class="badge badge-info">{{ $matiere->filieres->count() }} filière(s)</span>
                                    @else
                                        <span class="text-muted">Aucune</span>
                                    @endif
                                </td>
                                <td>
                                    @if($matiere->niveaux->count() > 0)
                                        <span class="badge badge-success">{{ $matiere->niveaux->count() }} niveau(x)</span>
                                    @else
                                        <span class="text-muted">Aucun</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('matieres.show', $matiere) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('matieres.edit', $matiere) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteMatiere({{ $matiere->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucune matière trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $matieres->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function deleteMatiere(matiereId) {
    if(confirm('Êtes-vous sûr de vouloir supprimer cette matière ?')) {
        fetch(`/matieres/${matiereId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            alert('Erreur lors de la suppression');
        });
    }
}
</script>
@endsection