@extends('layouts.app')

@section('title', 'Liste des Années Scolaires')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Liste des Années Scolaires</h1>
        <a href="{{ route('annees-scolaires.create') }}" class="btn btn-primary">Ajouter une année</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Périodes</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($annees as $annee)
                        <tr>
                            <td>{{ $annee->nom }}</td>
                            <td>{{ $annee->debut->format('Y-m-d') }}</td>
                            <td>{{ $annee->fin->format('Y-m-d') }}</td>
                            <td>{{ $annee->periodes_count }}</td>
                            <td>{{ $annee->is_active ? 'Oui' : 'Non' }}</td>
                            <td>
                                <a href="{{ route('annees-scolaires.show', $annee) }}" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{ route('annees-scolaires.edit', $annee) }}" class="btn btn-sm btn-warning">Modifier</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection