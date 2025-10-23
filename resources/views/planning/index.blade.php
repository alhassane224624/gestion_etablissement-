@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-calendar-alt"></i> Planning des Cours</h2>
                <a href="{{ route('planning.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un cours
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation semaine et filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('planning.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label>Semaine</label>
                        <div class="input-group">
                            <input type="week" name="semaine" class="form-control" value="{{ $semaine }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Classe</label>
                        <select name="classe_id" class="form-control">
                            <option value="">Toutes les classes</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}" {{ $classe_id == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->nom }} - {{ $classe->niveau->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Professeur</label>
                        <select name="professeur_id" class="form-control">
                            <option value="">Tous les professeurs</option>
                            @foreach($professeurs as $professeur)
                                <option value="{{ $professeur->id }}" {{ $professeur_id == $professeur->id ? 'selected' : '' }}>
                                    {{ $professeur->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Salle</label>
                        <select name="salle_id" class="form-control">
                            <option value="">Toutes les salles</option>
                            @foreach($salles as $salle)
                                <option value="{{ $salle->id }}" {{ $salle_id == $salle->id ? 'selected' : '' }}>
                                    {{ $salle->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('planning.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </a>
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

    <!-- Planning hebdomadaire -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 100px;">Heure</th>
                            @foreach($planning_semaine as $jour => $data)
                                <th class="text-center">
                                    <strong>{{ $jour }}</strong><br>
                                    <small class="text-muted">{{ $data['date']->format('d/m/Y') }}</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(range(8, 17) as $heure)
                            <tr>
                                <td class="text-center align-middle">
                                    <strong>{{ sprintf('%02d:00', $heure) }}</strong>
                                </td>
                                @foreach($planning_semaine as $jour => $data)
                                    <td class="p-1">
                                        @php
                                            $coursHeure = $data['cours']->filter(function($cours) use ($heure) {
                                                $heureDebut = (int) explode(':', $cours->heure_debut)[0];
                                                return $heureDebut == $heure;
                                            });
                                        @endphp
                                        
                                        @foreach($coursHeure as $cours)
                                            <div class="card mb-1 border-left-{{ $cours->statut_color }}" style="border-left-width: 4px !important;">
                                                <div class="card-body p-2">
                                                    <small>
                                                        <strong>{{ $cours->matiere->nom }}</strong><br>
                                                        <i class="fas fa-clock"></i> {{ $cours->heure_debut }} - {{ $cours->heure_fin }}<br>
                                                        <i class="fas fa-door-open"></i> {{ $cours->salle->nom }}<br>
                                                        <i class="fas fa-users"></i> {{ $cours->classe->nom }}<br>
                                                        <i class="fas fa-user-tie"></i> {{ $cours->professeur->name }}<br>
                                                        <span class="badge badge-{{ $cours->statut_color }} badge-sm">
                                                            {{ $cours->statut_libelle }}
                                                        </span>
                                                    </small>
                                                    <div class="mt-1">
                                                        <a href="{{ route('planning.edit', $cours) }}" class="btn btn-xs btn-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($cours->canBeValidated())
                                                            <form action="{{ route('planning.valider', $cours) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-xs btn-success" title="Valider">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <button type="button" class="btn btn-xs btn-danger" onclick="deletePlanning({{ $cours->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @if($coursHeure->isEmpty())
                                            <div class="text-center text-muted" style="min-height: 50px;">
                                                <small>-</small>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Légende -->
    <div class="card mt-3">
        <div class="card-body">
            <h6>Légende des statuts :</h6>
            <span class="badge badge-secondary">Brouillon</span>
            <span class="badge badge-success">Validé</span>
            <span class="badge badge-info">En cours</span>
            <span class="badge badge-primary">Terminé</span>
            <span class="badge badge-danger">Annulé</span>
        </div>
    </div>
</div>

<script>
function deletePlanning(planningId) {
    if(confirm('Êtes-vous sûr de vouloir supprimer ce cours du planning ?')) {
        fetch(`/planning/${planningId}`, {
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

<style>
.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.75rem;
    line-height: 1.5;
}

.border-left-primary { border-left-color: #007bff !important; }
.border-left-secondary { border-left-color: #6c757d !important; }
.border-left-success { border-left-color: #28a745 !important; }
.border-left-danger { border-left-color: #dc3545 !important; }
.border-left-warning { border-left-color: #ffc107 !important; }
.border-left-info { border-left-color: #17a2b8 !important; }
</style>
@endsection