<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 30px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #999; padding: 8px; text-align: left; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #666; }
        .badge { padding: 5px 8px; border-radius: 4px; color: #fff; }
        .bg-success { background: #28a745; }
        .bg-warning { background: #ffc107; }
        .bg-danger { background: #dc3545; }
    </style>
</head>
<body>

<div class="header">
    <h2>🎓 Reçu de Paiement</h2>
    <p><strong>École :</strong> {{ config('app.name') }}</p>
    <p><strong>Date :</strong> {{ $paiement->date_paiement->format('d/m/Y') }}</p>
</div>

<h4>Informations du Stagiaire</h4>
<p>
    <strong>Nom :</strong> {{ $stagiaire->prenom }} {{ $stagiaire->nom }}<br>
    <strong>Matricule :</strong> {{ $stagiaire->matricule }}<br>
    <strong>Filière :</strong> {{ optional($stagiaire->filiere)->nom ?? '-' }}<br>
    <strong>Classe :</strong> {{ optional($stagiaire->classe)->nom ?? '-' }}
</p>

<h4>Détails du Paiement</h4>
<table class="table">
    <tr>
        <th>Numéro de Transaction</th>
        <td>{{ $paiement->numero_transaction }}</td>
    </tr>
    <tr>
        <th>Montant</th>
        <td>{{ number_format($paiement->montant, 2) }} DH</td>
    </tr>
    <tr>
        <th>Méthode</th>
        <td>{{ $paiement->methode_paiement ?? '-' }}</td>
    </tr>
    <tr>
        <th>Statut</th>
        @php
            $color = match($paiement->statut) {
                'valide' => 'success',
                'en_attente' => 'warning',
                'refuse' => 'danger',
                default => 'secondary'
            };
        @endphp
        <td><span class="badge bg-{{ $color }}">{{ ucfirst($paiement->statut) }}</span></td>
    </tr>
</table>

@if($paiement->echeanciers->count())
<h4>Échéances concernées</h4>
<table class="table">
    <thead>
        <tr>
            <th>Titre</th>
            <th>Date d’échéance</th>
            <th>Montant Affecté</th>
        </tr>
    </thead>
    <tbody>
        @foreach($paiement->echeanciers as $echeance)
            <tr>
                <td>{{ $echeance->titre ?? 'Échéance ' . $loop->iteration }}</td>
                <td>{{ $echeance->date_echeance->format('d/m/Y') }}</td>
                <td>{{ number_format($echeance->pivot->montant_affecte, 2) }} DH</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="footer">
    <p>Généré le {{ now()->format('d/m/Y H:i') }} - {{ config('app.name') }}</p>
</div>

</body>
</html>
