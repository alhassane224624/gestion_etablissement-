@extends('layouts.app')

@section('title', "Détails de l'Année Scolaire")

@section('content')
    <h1 class="mb-4">{{ $anneeScolaire->nom }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Début :</strong> 
                {{ $anneeScolaire->debut ? $anneeScolaire->debut->format('Y-m-d') : 'Non défini' }}
            </p>
            <p><strong>Fin :</strong> 
                {{ $anneeScolaire->fin ? $anneeScolaire->fin->format('Y-m-d') : 'Non défini' }}
            </p>
            <p><strong>Active :</strong> 
                <span class="{{ $anneeScolaire->is_active ? 'text-success' : 'text-danger' }}">
                    {{ $anneeScolaire->is_active ? 'Oui' : 'Non' }}
                </span>
            </p>

            <hr>

            <h5 class="mt-4">📅 Périodes</h5>
            @if($anneeScolaire->periodes->isEmpty())
                <p class="text-muted">Aucune période enregistrée pour cette année scolaire.</p>
            @else
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anneeScolaire->periodes as $periode)
                            <tr>
                                <td>{{ $periode->nom }}</td>
                                <td>{{ $periode->type_libelle ?? ucfirst($periode->type) }}</td>
                                <td>{{ $periode->debut ? $periode->debut->format('Y-m-d') : '—' }}</td>
                                <td>{{ $periode->fin ? $periode->fin->format('Y-m-d') : '—' }}</td>
                                <td>
                                    <span class="{{ $periode->is_active ? 'text-success' : 'text-danger' }}">
                                        {{ $periode->is_active ? 'Oui' : 'Non' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <hr>

            <h5 class="mt-4">📊 Statistiques</h5>
            <p><strong>Total des périodes :</strong> {{ $stats['total_periodes'] ?? 0 }}</p>
            <p><strong>Période active :</strong> 
                {{ $stats['periode_active'] ? $stats['periode_active']->nom : 'Aucune' }}
            </p>
            <p><strong>Durée totale :</strong> {{ $stats['duree_totale'] ?? 0 }} jours</p>
            <p><strong>Progression :</strong> {{ $stats['progression'] ?? 0 }}%</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('annees-scolaires.edit', $anneeScolaire) }}" class="btn btn-warning">
            Modifier
        </a>
        <form action="{{ route('annees-scolaires.destroy', $anneeScolaire) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette année scolaire ?')">
                Supprimer
            </button>
        </form>

        <a href="{{ route('annees-scolaires.index') }}" class="btn btn-secondary">
            Retour
        </a>
    </div>
@endsection
