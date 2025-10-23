@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-door-open"></i> Gestion des Salles</h2>
                <a href="{{ route('salles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvelle Salle
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Salles</h6>
                    <h3>{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Disponibles</h6>
                    <h3>{{ $stats['disponibles'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Occupées aujourd'hui</h6>
                    <h3>{{ $stats['occupees_aujourd_hui'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Capacité totale</h6>
                    <h3>{{ $stats['capacite_totale'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('salles.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-control">
                            <option value="">Tous les types</option>
                            <option value="amphitheatre" {{ request('type') == 'amphitheatre' ? 'selected' : '' }}>Amphithéâtre</option>
                            <option value="salle_cours" {{ request('type') == 'salle_cours' ? 'selected' : '' }}>Salle de cours</option>
                            <option value="laboratoire" {{ request('type') == 'laboratoire' ? 'selected' : '' }}>Laboratoire</option>
                            <option value="salle_informatique" {{ request('type') == 'salle_informatique' ? 'selected' : '' }}>Salle informatique</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="batiment" class="form-control" placeholder="Bâtiment..." value="{{ request('batiment') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="disponible" class="form-control">
                            <option value="">Toutes</option>
                            <option value="1" {{ request('disponible') == '1' ? 'selected' : '' }}>Disponibles</option>
                            <option value="0" {{ request('disponible') == '0' ? 'selected' : '' }}>Indisponibles</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Filtrer
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

    <!-- Liste des salles -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Capacité</th>
                            <th>Bâtiment</th>
                            <th>Étage</th>
                            <th>Équipements</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salles as $salle)
                            <tr>
                                <td><strong>{{ $salle->nom }}</strong></td>
                                <td>
                                    @if($salle->type == 'amphitheatre')
                                        <span class="badge badge-purple">Amphithéâtre</span>
                                    @elseif($salle->type == 'salle_cours')
                                        <span class="badge badge-primary">Salle de cours</span>
                                    @elseif($salle->type == 'laboratoire')
                                        <span class="badge badge-warning">Laboratoire</span>
                                    @else
                                        <span class="badge badge-info">Salle informatique</span>
                                    @endif
                                </td>
                                <td><i class="fas fa-users"></i> {{ $salle->capacite }}</td>
                                <td>{{ $salle->batiment ?? '-' }}</td>
                                <td>{{ $salle->etage ?? '-' }}</td>
                                <td>
                                    @if($salle->equipements && count($salle->equipements) > 0)
                                        <span class="badge badge-light">{{ count($salle->equipements) }} équipements</span>
                                    @else
                                        <span class="text-muted">Aucun</span>
                                    @endif
                                </td>
                                <td>
                                    @if($salle->disponible)
                                        <span class="badge badge-success">Disponible</span>
                                    @else
                                        <span class="badge badge-danger">Indisponible</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('salles.show', $salle) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('salles.planning', $salle) }}" class="btn btn-sm btn-primary" title="Planning">
                                            <i class="fas fa-calendar"></i>
                                        </a>
                                        <a href="{{ route('salles.edit', $salle) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('salles.toggle-disponibilite', $salle) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $salle->disponible ? 'btn-secondary' : 'btn-success' }}" title="{{ $salle->disponible ? 'Marquer indisponible' : 'Marquer disponible' }}">
                                                <i class="fas fa-{{ $salle->disponible ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteSalle({{ $salle->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucune salle trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $salles->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function deleteSalle(salleId) {
    if(confirm('Êtes-vous sûr de vouloir supprimer cette salle ?')) {
        fetch(`/salles/${salleId}`, {
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

<style>
.badge-purple {
    background-color: #6f42c1;
    color: white;
}
</style>
@endsection