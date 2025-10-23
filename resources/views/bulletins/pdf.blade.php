<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin - {{ $bulletin->stagiaire->nom }} {{ $bulletin->stagiaire->prenom }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            margin: 20px;
            font-size: 11px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #2563eb;
        }
        .info-section {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            display: table-cell;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left; 
        }
        .table th { 
            background-color: #1f2937; 
            color: white;
            font-weight: bold;
        }
        .table td.center, .table th.center {
            text-align: center;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .summary-row {
            margin-bottom: 10px;
        }
        .summary-total {
            font-size: 14px;
            font-weight: bold;
            background-color: #1f2937;
            color: white;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BULLETIN SCOLAIRE</h1>
        <p>Année Scolaire {{ $bulletin->periode->anneeScolaire->nom ?? 'N/A' }}</p>
        <p>Période: {{ $bulletin->periode->nom }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom complet:</div>
            <div class="info-value">{{ strtoupper($bulletin->stagiaire->nom) }} {{ ucfirst($bulletin->stagiaire->prenom) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Matricule:</div>
            <div class="info-value">{{ $bulletin->stagiaire->matricule }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Classe:</div>
            <div class="info-value">{{ $bulletin->classe->nom }} - {{ $bulletin->classe->niveau->nom ?? 'N/A' }} ({{ $bulletin->classe->filiere->nom ?? 'N/A' }})</div>
        </div>
        <div class="info-row">
            <div class="info-label">Classement:</div>
            <div class="info-value">{{ $bulletin->rang }}{{ $bulletin->rang == 1 ? 'er' : 'ème' }} / {{ $bulletin->total_classe }}</div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Matière</th>
                <th class="center">Coefficient</th>
                <th class="center">Moyenne</th>
                <th class="center">Points</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPoints = 0;
                $totalCoef = 0;
                // ✅ S'assurer que moyennes_matieres est un tableau
                $moyennes = is_array($bulletin->moyennes_matieres) 
                    ? $bulletin->moyennes_matieres 
                    : json_decode($bulletin->moyennes_matieres, true) ?? [];
            @endphp
            
            @foreach ($moyennes as $matiere)
                @php
                    // ✅ Vérification que $matiere est un tableau
                    if (!is_array($matiere)) {
                        continue;
                    }
                    
                    // ✅ Récupérer les valeurs avec des valeurs par défaut
                    $moyenne = isset($matiere['moyenne']) && is_numeric($matiere['moyenne']) 
                        ? (float) $matiere['moyenne'] 
                        : 0;
                    
                    $coefficient = isset($matiere['coefficient']) && is_numeric($matiere['coefficient']) 
                        ? (int) $matiere['coefficient'] 
                        : 1;
                    
                    $points = $moyenne * $coefficient;
                    $totalPoints += $points;
                    $totalCoef += $coefficient;
                @endphp
                <tr>
                    <td>{{ $matiere['matiere'] ?? 'N/A' }}</td>
                    <td class="center">{{ $coefficient }}</td>
                    <td class="center">{{ number_format($moyenne, 2) }}</td>
                    <td class="center">{{ number_format($points, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <strong>Total Points:</strong> {{ number_format($totalPoints, 2) }}
        </div>
        <div class="summary-row">
            <strong>Total Coefficients:</strong> {{ $totalCoef }}
        </div>
        <div class="summary-total">
            MOYENNE GÉNÉRALE: {{ number_format($bulletin->moyenne_generale, 2) }}/20
        </div>
    </div>

    <div class="info-section" style="margin-top: 20px;">
        <strong>Appréciation générale:</strong><br>
        {{ $bulletin->appreciation_generale ?? 'Aucune appréciation' }}
    </div>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Ce bulletin est un document officiel</p>
    </div>
</body>
</html>