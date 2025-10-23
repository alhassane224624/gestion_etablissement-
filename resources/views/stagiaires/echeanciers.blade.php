@extends('layouts.app-stagiaire')

@section('title', 'Mes Échéanciers')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary fw-bold">📅 Mes Échéanciers</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Impayés</h6>
                    <h3 class="text-danger">{{ $stats['impayes'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Payés partiellement</h6>
                    <h3 class="text-warning">{{ $stats['partiels'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>Payés</h6>
                    <h3 class="text-success">{{ $stats['payes'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <h6>En retard</h6>
                    <h3 class="text-danger">{{ $stats['retards'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Titre</th>
                    <th>Montant</th>
                    <th>Payé</th>
                    <th>Restant</th>
                    <th>Date d’échéance</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($echeanciers as $echeance)
                    <tr>
                        <td>{{ $echeance->titre ?? 'Échéance ' . $loop->iteration }}</td>
                        <td>{{ number_format($echeance->montant, 2) }} DH</td>
                        <td>{{ number_format($echeance->montant_paye, 2) }} DH</td>
                        <td>{{ number_format($echeance->montant_restant, 2) }} DH</td>
                        <td>{{ $echeance->date_echeance->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $color = match($echeance->statut) {
                                    'impaye' => 'danger',
                                    'paye_partiel' => 'warning',
                                    'paye' => 'success',
                                    'en_retard' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $echeance->statut)) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aucun échéancier trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
