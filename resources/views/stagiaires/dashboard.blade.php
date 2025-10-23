@extends('layouts.app-stagiaire') <!-- ✅ Doit toujours être en premier -->

@section('title', 'Dashboard Stagiaire')
@section('page-title', 'Mon Tableau de Bord')
@section('content')
<div class="container-fluid py-4">
    <h3 class="fw-bold text-primary mb-4">
        👋 Bonjour {{ $stagiaire->prenom ?? Auth::user()->name }}, bienvenue dans votre espace stagiaire
    </h3>

    <!-- ============================= -->
    <!-- 🧮 Statistiques principales -->
    <!-- ============================= -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                    <h6 class="text-muted mb-1">Moyenne Générale</h6>
                    <h4 class="fw-bold">{{ number_format($moyenne_generale, 2) }}/20</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="fas fa-file-invoice-dollar fa-2x text-success mb-2"></i>
                    <h6 class="text-muted mb-1">Total Payé</h6>
                    <h4 class="fw-bold text-success">{{ number_format($total_paye, 2) }} DH</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="fas fa-wallet fa-2x text-warning mb-2"></i>
                    <h6 class="text-muted mb-1">Solde Restant</h6>
                    <h4 class="fw-bold text-warning">{{ number_format($solde_restant, 2) }} DH</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-info mb-2"></i>
                    <h6 class="text-muted mb-1">Statut Paiement</h6>
                    <span class="badge bg-{{ 
                        $statut_paiement === 'a_jour' ? 'success' : 
                        ($statut_paiement === 'en_retard' ? 'danger' : 'warning') }}">
                        {{ ucfirst(str_replace('_', ' ', $statut_paiement)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================= -->
    <!-- 🧾 Paiements récents -->
    <!-- ============================= -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-receipt me-2 text-primary"></i>Derniers Paiements</h5>
            <a href="{{ route('stagiaire.paiements') }}" class="btn btn-sm btn-outline-primary">
                Voir tout <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body">
            @if($stagiaire->paiements && $stagiaire->paiements->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Statut</th>
                                <th>Reçu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stagiaire->paiements->take(5) as $paiement)
                                <tr>
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
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">Aucun paiement enregistré pour le moment.</p>
            @endif
        </div>
    </div>

    <!-- ============================= -->
    <!-- 📅 Prochaines échéances -->
    <!-- ============================= -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-calendar-alt me-2 text-danger"></i>Échéances à venir</h5>
            <a href="{{ route('stagiaire.echeanciers') }}" class="btn btn-sm btn-outline-danger">
                Voir tout <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body">
            @if($stagiaire->echeanciers && $stagiaire->echeanciers->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Montant</th>
                                <th>Date d’échéance</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stagiaire->echeanciers->sortBy('date_echeance')->take(5) as $echeance)
                                <tr>
                                    <td>{{ $echeance->titre ?? 'Échéance ' . $loop->iteration }}</td>
                                    <td>{{ number_format($echeance->montant, 2) }} DH</td>
                                    <td>{{ $echeance->date_echeance->format('d/m/Y') }}</td>
                                    @php
                                        $color = match($echeance->statut) {
                                            'impaye' => 'danger',
                                            'paye_partiel' => 'warning',
                                            'paye' => 'success',
                                            'en_retard' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <td><span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $echeance->statut)) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">Aucune échéance enregistrée pour le moment.</p>
            @endif
        </div>
    </div>

    <!-- ============================= -->
    <!-- 📢 Dernier bulletin validé -->
    <!-- ============================= -->
    @if($bulletin_valide)
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="fas fa-file-alt me-2 text-success"></i>Dernier Bulletin Validé</h5>
            <a href="{{ route('stagiaire.bulletin.telecharger', $bulletin_valide->id) }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-download me-1"></i> Télécharger
            </a>
        </div>
        <div class="card-body">
            <p><strong>Période :</strong> {{ $bulletin_valide->periode->nom ?? '-' }}</p>
            <p><strong>Validé le :</strong> {{ \Carbon\Carbon::parse($bulletin_valide->validated_at)->format('d/m/Y') }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
