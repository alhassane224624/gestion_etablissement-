@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-file-alt"></i> Gestion des Bulletins</h2>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateModal">
                    <i class="fas fa-cog"></i> Générer des Bulletins
                </button>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('bulletins.index') }}">
                <div class="row">
                    <div class="col-md-5">
                        <label>Classe</label>
                        <select name="classe_id" class="form-control">
                            <option value="">Toutes les classes</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->nom }} - {{ $classe->niveau->nom }} ({{ $classe->filiere->nom }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label>Période</label>
                        <select name="periode_id" class="form-control">
                            <option value="">Toutes les périodes</option>
                            @foreach($periodes as $periode)
                                <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nom }} ({{ $periode->anneeScolaire->nom }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-filter"></i> Filtrer
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

    <!-- Liste des bulletins -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Stagiaire</th>
                            <th>Classe</th>
                            <th>Période</th>
                            <th>Moyenne Générale</th>
                            <th>Rang</th>
                            <th>Appréciation</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bulletins as $bulletin)
                            <tr>
                                <td>
                                    <a href="{{ route('stagiaires.show', $bulletin->stagiaire) }}">
                                        {{ $bulletin->stagiaire->nom_complet }}
                                    </a>
                                </td>
                                <td>{{ $bulletin->classe->nom }}</td>
                                <td>{{ $bulletin->periode->nom }}</td>
                                <td>
                                    <strong class="text-{{ $bulletin->moyenne_generale >= 10 ? 'success' : 'danger' }}">
                                        {{ number_format($bulletin->moyenne_generale, 2) }}/20
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ $bulletin->rang }}/{{ $bulletin->total_classe }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted" title="{{ $bulletin->appreciation_generale }}">
                                        {{ Str::limit($bulletin->appreciation_generale, 30) }}
                                    </span>
                                </td>
                                <td>
                                    @if($bulletin->validated_at)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Validé
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i> En attente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('bulletins.show', $bulletin) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('bulletins.download-pdf', $bulletin) }}" class="btn btn-sm btn-danger" title="Télécharger PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @if(!$bulletin->validated_at)
                                            <form action="{{ route('bulletins.validate', $bulletin) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" title="Valider">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun bulletin trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $bulletins->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal de génération -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Générer des Bulletins</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('bulletins.generate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="gen_classe_id">Classe <span class="text-danger">*</span></label>
                        <select name="classe_id" id="gen_classe_id" class="form-control" required>
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}">
                                    {{ $classe->nom }} - {{ $classe->niveau->nom }} ({{ $classe->filiere->nom }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gen_periode_id">Période <span class="text-danger">*</span></label>
                        <select name="periode_id" id="gen_periode_id" class="form-control" required>
                            <option value="">Sélectionner une période</option>
                            @foreach($periodes as $periode)
                                <option value="{{ $periode->id }}">
                                    {{ $periode->nom }} ({{ $periode->anneeScolaire->nom }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Les bulletins seront générés pour tous les stagiaires de la classe sélectionnée qui ont des notes pour la période choisie.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cog"></i> Générer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection