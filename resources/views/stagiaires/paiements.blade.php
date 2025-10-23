@extends('layouts.app-stagiaire')

@section('title', 'Mes Paiements')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-primary fw-bold">💳 Mes Paiements</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Total payé :</strong> {{ number_format($stagiaire->total_paye, 2) }} DH</p>
            <p><strong>Montant restant :</strong> {{ number_format($stagiaire->solde_restant, 2) }} DH</p>
            <p><strong>Statut :</strong> 
                <span class="badge bg-{{ $stagiaire->statut_paiement === 'a_jour' ? 'success' : ($stagiaire->statut_paiement === 'en_retard' ? 'danger' : 'warning') }}">
                    {{ ucfirst(str_replace('_', ' ', $stagiaire->statut_paiement)) }}
                </span>
            </p>
        </div>
    </div>

    <div class="table-responsive shadow-sm">
        <table class="table table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Statut</th>
                    <th>Reçu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($paiements as $paiement)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                        <td>{{ number_format($paiement->montant, 2) }} DH</td>
                        <td>{{ $paiement->methode_paiement ?? '-' }}</td>
                        <td>
                            @php
                                $color = match($paiement->statut) {
                                    'valide' => 'success',
                                    'refuse' => 'danger',
                                    'en_attente' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($paiement->statut) }}</span>
                        </td>
                        <td>
                            @if($paiement->statut === 'valide')
                                <a href="{{ route('stagiaire.paiement.recu', $paiement->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-pdf"></i> Reçu
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aucun paiement trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $paiements->links() }}
    </div>
</div>
@endsection
