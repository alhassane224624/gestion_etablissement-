@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-users"></i> Gestion des stagiaires</h2>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvel stagiaire
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Stagiaires</h6>
                    <h3>{{ $stats['total_users'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Administrateurs</h6>
                    <h3>{{ $stats['admins'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Professeurs</h6>
                    <h3>{{ $stats['professeurs'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Stagiaires Actifs</h6>
                    <h3>{{ $stats['actifs'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par nom ou email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-control">
                            <option value="">Tous les rôles</option>
                            <option value="administrateur" {{ request('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            <option value="professeur" {{ request('role') == 'professeur' ? 'selected' : '' }}>Professeur</option>
                            <option value="stagiaire" {{ request('role') == 'stagiaire' ? 'selected' : '' }}>Stagiaire</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="is_active" class="form-control">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Actifs</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactifs</option>
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

    <!-- Messages -->
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

    <!-- Liste des utilisateurs -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Spécialité</th>
                            <th>Téléphone</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'administrateur')
                                        <span class="badge badge-danger">Administrateur</span>
                                    @elseif($user->role == 'professeur')
                                        <span class="badge badge-info">Professeur</span>
                                    @else
                                        <span class="badge badge-secondary">Stagiaires</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge badge-success">Actif</span>
                                    @else
                                        <span class="badge badge-danger">Inactif</span>
                                    @endif
                                </td>
                                <td>{{ $user->specialite ?? '-' }}</td>
                                <td>{{ $user->telephone ?? '-' }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id != auth()->id())
                                           <form action="{{ route('users.toggle-active', $user->id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-sm btn-warning">
        {{ $user->is_active ? 'Désactiver' : 'Activer' }}
    </button>
</form>

                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteUser({{ $user->id }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun stagiaire trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(userId) {
    if(confirm('Êtes-vous sûr de vouloir supprimer cet stagiaire ?')) {
        fetch(`/users/${userId}`, {
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
            }
        });
    }
}
</script>
@endsection