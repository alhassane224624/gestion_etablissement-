<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Financier</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4e73df;
        }
        
        .header h1 {
            font-size: 20px;
            color: #4e73df;
            margin-bottom: 5px;
        }
        
        .header .periode {
            font-size: 11px;
            color: #666;
        }
        
        .info-box {
            background: #f8f9fc;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stat-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            background: #f8f9fc;
            border: 1px solid #e3e6f0;
        }
        
        .stat-item .label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-item .value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .stat-item.primary .value {
            color: #4e73df;
        }
        
        .stat-item.success .value {
            color: #1cc88a;
        }
        
        .stat-item.warning .value {
            color: #f6c23e;
        }
        
        .stat-item.danger .value {
            color: #e74a3b;
        }
        
        h2 {
            font-size: 14px;
            color: #4e73df;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #e3e6f0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
        }
        
        table thead {
            background: #4e73df;
            color: white;
        }
        
        table th, table td {
            padding: 6px;
            text-align: left;
            border: 1px solid #e3e6f0;
        }
        
        table tbody tr:nth-child(even) {
            background: #f8f9fc;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        
        .badge-success {
            background: #1cc88a;
            color: white;
        }
        
        .badge-danger {
            background: #e74a3b;
            color: white;
        }
        
        .badge-warning {
            background: #f6c23e;
            color: #333;
        }
        
        .badge-info {
            background: #36b9cc;
            color: white;
        }
        
        .badge-secondary {
            background: #858796;
            color: white;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #666;
            padding-top: 10px;
            border-top: 1px solid #e3e6f0;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-cell {
            display: table-cell;
            padding: 5px;
            width: 50%;
        }
        
        .summary-box {
            background: #f8f9fc;
            padding: 8px;
            border-left: 3px solid #4e73df;
        }
        
        .summary-box .title {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .summary-box .amount {
            font-size: 13px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>RAPPORT FINANCIER</h1>
        <div class="periode">
            Période: {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} 
            au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
        </div>
        <div class="periode">
            Généré le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="stats-grid">
        <div class="stat-item primary">
            <div class="label">Total Encaissé</div>
            <div class="value">{{ number_format($stats['total_encaisse'], 2) }} DH</div>
        </div>
        <div class="stat-item warning">
            <div class="label">Total Attendu</div>
            <div class="value">{{ number_format($stats['total_attendu'], 2) }} DH</div>
        </div>
        <div class="stat-item danger">
            <div class="label">Total Impayés</div>
            <div class="value">{{ number_format($stats['total_impayes'], 2) }} DH</div>
        </div>
        <div class="stat-item success">
            <div class="label">Taux Recouvrement</div>
            <div class="value">{{ $stats['taux_recouvrement'] }}%</div>
        </div>
    </div>

    <!-- Résumé supplémentaire -->
    <div class="summary-grid">
        <div class="summary-row">
            <div class="summary-cell">
                <div class="summary-box">
                    <div class="title">Remises Accordées</div>
                    <div class="amount">{{ number_format($stats['total_remises'], 2) }} DH</div>
                    <div style="font-size: 8px; color: #666;">{{ $stats['nb_remises'] }} remises actives</div>
                </div>
            </div>
            <div class="summary-cell">
                <div class="summary-box">
                    <div class="title">Retards de Paiement</div>
                    <div class="amount">{{ $stats['nb_retards'] }}</div>
                    <div style="font-size: 8px; color: #666;">échéances en retard</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par méthode de paiement -->
    <h2>Répartition par Méthode de Paiement</h2>
    <table>
        <thead>
            <tr>
                <th>Méthode</th>
                <th class="text-right">Montant Total</th>
                <th class="text-center">Pourcentage</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalMethodes = array_sum($stats['par_methode']);
            @endphp
            @foreach($stats['par_methode'] as $methode => $montant)
                <tr>
                    <td>{{ $methode }}</td>
                    <td class="text-right">{{ number_format($montant, 2) }} DH</td>
                    <td class="text-center">
                        {{ $totalMethodes > 0 ? number_format(($montant / $totalMethodes) * 100, 1) : 0 }}%
                    </td>
                </tr>
            @endforeach
            <tr style="background: #e3e6f0; font-weight: bold;">
                <td>TOTAL</td>
                <td class="text-right">{{ number_format($totalMethodes, 2) }} DH</td>
                <td class="text-center">100%</td>
            </tr>
        </tbody>
    </table>

    <!-- Liste des paiements -->
    <h2>Détail des Paiements</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Stagiaire</th>
                <th>Filière</th>
                <th>Méthode</th>
                <th class="text-right">Montant</th>
                <th>Référence</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paiements as $paiement)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                    <td>{{ $paiement->stagiaire->nom }} {{ $paiement->stagiaire->prenom }}</td>
                    <td>
                        <span class="badge badge-info">{{ $paiement->stagiaire->filiere->nom }}</span>
                    </td>
                    <td>{{ $paiement->methode_paiement }}</td>
                    <td class="text-right">{{ number_format($paiement->montant, 2) }} DH</td>
                    <td>{{ $paiement->reference ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #999;">Aucun paiement pour cette période</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #4e73df; color: white; font-weight: bold;">
                <td colspan="4">TOTAL DES PAIEMENTS</td>
                <td class="text-right">{{ number_format($paiements->sum('montant'), 2) }} DH</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="page-break"></div>

    <!-- Échéanciers -->
    <h2>Échéanciers de la Période</h2>
    <table>
        <thead>
            <tr>
                <th>Date Échéance</th>
                <th>Stagiaire</th>
                <th>Filière</th>
                <th>Statut</th>
                <th class="text-right">Montant</th>
                <th class="text-right">Montant Restant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($echeanciers as $echeancier)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($echeancier->date_echeance)->format('d/m/Y') }}</td>
                    <td>{{ $echeancier->stagiaire->nom }} {{ $echeancier->stagiaire->prenom }}</td>
                    <td>
                        <span class="badge badge-info">{{ $echeancier->stagiaire->filiere->nom }}</span>
                    </td>
                    <td>
                        @if($echeancier->statut === 'paye')
                            <span class="badge badge-success">Payé</span>
                        @elseif($echeancier->statut === 'en_retard')
                            <span class="badge badge-danger">En retard</span>
                        @elseif($echeancier->statut === 'paye_partiel')
                            <span class="badge badge-warning">Partiel</span>
                        @else
                            <span class="badge badge-secondary">{{ $echeancier->statut }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($echeancier->montant, 2) }} DH</td>
                    <td class="text-right">{{ number_format($echeancier->montant_restant, 2) }} DH</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #999;">Aucun échéancier pour cette période</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #4e73df; color: white; font-weight: bold;">
                <td colspan="4">TOTAUX</td>
                <td class="text-right">{{ number_format($echeanciers->sum('montant'), 2) }} DH</td>
                <td class="text-right">{{ number_format($echeanciers->sum('montant_restant'), 2) }} DH</td>
            </tr>
        </tfoot>
    </table>

    <!-- Analyse par filière -->
    @if($paiements->isNotEmpty())
        <h2>Analyse par Filière</h2>
        <table>
            <thead>
                <tr>
                    <th>Filière</th>
                    <th class="text-center">Nombre de Paiements</th>
                    <th class="text-right">Montant Total</th>
                    <th class="text-center">% du Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $parFiliere = $paiements->groupBy('stagiaire.filiere.nom');
                    $totalGeneral = $paiements->sum('montant');
                @endphp
                @foreach($parFiliere as $nomFiliere => $paiementsFiliere)
                    @php
                        $totalFiliere = $paiementsFiliere->sum('montant');
                    @endphp
                    <tr>
                        <td>{{ $nomFiliere }}</td>
                        <td class="text-center">{{ $paiementsFiliere->count() }}</td>
                        <td class="text-right">{{ number_format($totalFiliere, 2) }} DH</td>
                        <td class="text-center">
                            {{ $totalGeneral > 0 ? number_format(($totalFiliere / $totalGeneral) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                @endforeach
                <tr style="background: #e3e6f0; font-weight: bold;">
                    <td>TOTAL</td>
                    <td class="text-center">{{ $paiements->count() }}</td>
                    <td class="text-right">{{ number_format($totalGeneral, 2) }} DH</td>
                    <td class="text-center">100%</td>
                </tr>
            </tbody>
        </table>
    @endif

    <!-- Pied de page -->
    <div class="footer">
        <p>© {{ now()->format('Y') }} - Système de Gestion des Paiements - Document confidentiel</p>
        <p>Page <span class="pagenum"></span></p>
    </div>
</body>
</html>