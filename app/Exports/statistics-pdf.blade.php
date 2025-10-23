<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Statistiques</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #2980b9;
            margin-top: 30px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .stat-card {
            display: inline-block;
            width: 23%;
            padding: 15px;
            margin: 10px 1%;
            background: #ecf0f1;
            border-radius: 5px;
            text-align: center;
        }
        .stat-card h3 {
            margin: 5px 0;
            color: #2c3e50;
        }
        .stat-card p {
            margin: 5px 0;
            color: #7f8c8d;
        }
        .header-info {
            text-align: center;
            margin-bottom: 30px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <h1>📊 Rapport Statistiques</h1>
    <div class="header-info">
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Statistiques Générales -->
    <h2>Statistiques Générales</h2>
    <table>
        <tr>
            <th>Indicateur</th>
            <th>Valeur</th>
        </tr>
        <tr>
            <td>Total Stagiaires</td>
            <td><strong>{{ $stats['general']['total_stagiaires'] }}</strong></td>
        </tr>
        <tr>
            <td>Total Filières</td>
            <td><strong>{{ $stats['general']['total_filieres'] }}</strong></td>
        </tr>
        <tr>
            <td>Total Professeurs</td>
            <td><strong>{{ $stats['general']['total_professeurs'] }}</strong></td>
        </tr>
        <tr>
            <td>Total Notes</td>
            <td><strong>{{ $stats['general']['total_notes'] }}</strong></td>
        </tr>
        <tr>
            <td>Moyenne Générale</td>
            <td><strong>{{ number_format($stats['general']['moyenne_generale'], 2) }}/20</strong></td>
        </tr>
    </table>

    <!-- Statistiques par Filière -->
    <h2>Performances par Filière</h2>
    <table>
        <thead>
            <tr>
                <th>Filière</th>
                <th>Niveau</th>
                <th>Stagiaires</th>
                <th>Notes</th>
                <th>Moyenne</th>
                <th>Taux Réussite</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['filieres'] as $filiere)
                <tr>
                    <td>{{ $filiere['nom'] }}</td>
                    <td>{{ $filiere['niveau'] }}</td>
                    <td>{{ $filiere['stagiaires_count'] }}</td>
                    <td>{{ $filiere['notes_count'] }}</td>
                    <td>{{ number_format($filiere['moyenne'] ?? 0, 2) }}/20</td>
                    <td>{{ number_format($filiere['taux_reussite'], 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Distribution des Notes -->
    <h2>Distribution des Notes</h2>
    <table>
        <tr>
            <th>Mention</th>
            <th>Nombre</th>
        </tr>
        <tr>
            <td>Excellent (≥16)</td>
            <td>{{ $stats['notes']['distribution']['excellent'] }}</td>
        </tr>
        <tr>
            <td>Bien (14-16)</td>
            <td>{{ $stats['notes']['distribution']['bien'] }}</td>
        </tr>
        <tr>
            <td>Assez Bien (12-14)</td>
            <td>{{ $stats['notes']['distribution']['assez_bien'] }}</td>
        </tr>
        <tr>
            <td>Passable (10-12)</td>
            <td>{{ $stats['notes']['distribution']['passable'] }}</td>
        </tr>
        <tr>
            <td>Insuffisant (<10)</td>
            <td>{{ $stats['notes']['distribution']['insuffisant'] }}</td>
        </tr>
    </table>

    <!-- Statistiques par Matière -->
    <h2>Performances par Matière</h2>
    <table>
        <thead>
            <tr>
                <th>Matière</th>
                <th>Total Notes</th>
                <th>Moyenne</th>
                <th>Note Max</th>
                <th>Note Min</th>
                <th>Réussites</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['notes']['par_matiere'] as $matiere)
                <tr>
                    <td>{{ $matiere->matiere }}</td>
                    <td>{{ $matiere->total }}</td>
                    <td>{{ number_format($matiere->moyenne, 2) }}/20</td>
                    <td>{{ number_format($matiere->note_max, 2) }}</td>
                    <td>{{ number_format($matiere->note_min, 2) }}</td>
                    <td>{{ $matiere->reussites }}/{{ $matiere->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>exportsexports