<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bulletin - {{ $stagiaire->matricule }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #3b82f6;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header h2 {
            color: #666;
            font-size: 16px;
            font-weight: normal;
        }

        .info-section {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background: #f9fafb;
        }

        .info-row {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 150px;
        }

        .info-value {
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        th {
            background-color: #3b82f6;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .moyenne-cell {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }

        .appreciation-section {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9fafb;
        }

        .appreciation-section h3 {
            color: #3b82f6;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .total-row {
            background-color: #3b82f6 !important;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }

        .total-row td {
            border-color: #2563eb;
            padding: 12px 8px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #10b981;
            color: white;
        }

        .badge-danger {
            background-color: #ef4444;
            color: white;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>📋 BULLETIN SCOLAIRE</h1>
        <h2>{{ $bulletin->periode->nom ?? 'Période non définie' }}</h2>
        @if($bulletin->periode && $bulletin->periode->anneeScolaire)
            <p style="color: #666; margin-top: 5px;">
                Année scolaire {{ $bulletin->periode->anneeScolaire->nom }}
            </p>
        @endif
    </div>

    <!-- Informations stagiaire -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Nom complet :</span>
            <span class="info-value">{{ $stagiaire->prenom }} {{ $stagiaire->nom }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Matricule :</span>
            <span class="info-value">{{ $stagiaire->matricule }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Filière :</span>
            <span class="info-value">{{ $stagiaire->filiere->nom ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Classe :</span>
            <span class="info-value">{{ $bulletin->classe->nom ?? 'N/A' }}</span>
        </div>
    </div>

    <!-- Tableau des notes -->
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Matière</th>
                <th style="width: 15%; text-align: center;">Coefficient</th>
                <th style="width: 20%; text-align: center;">Moyenne</th>
                <th style="width: 25%; text-align: center;">Appréciation</th>
            </tr>
        </thead>
        <tbody>
            @if($bulletin->moyennes_matieres && count($bulletin->moyennes_matieres) > 0)
                @foreach($bulletin->moyennes_matieres as $moyenneMatiere)
                    <tr>
                        <td>{{ $moyenneMatiere['matiere'] ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ $moyenneMatiere['coefficient'] ?? '-' }}</td>
                        <td class="moyenne-cell">
                            {{ number_format($moyenneMatiere['moyenne'] ?? 0, 2) }}/20
                        </td>
                        <td style="text-align: center;">
                            @php
                                $moy = $moyenneMatiere['moyenne'] ?? 0;
                            @endphp
                            @if($moy >= 16)
                                <span class="badge badge-success">Excellent</span>
                            @elseif($moy >= 14)
                                <span class="badge badge-success">Très bien</span>
                            @elseif($moy >= 12)
                                <span class="badge badge-success">Bien</span>
                            @elseif($moy >= 10)
                                <span class="badge badge-success">Passable</span>
                            @else
                                <span class="badge badge-danger">Insuffisant</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #999;">
                        Aucune note disponible pour cette période
                    </td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" style="text-align: left;">
                    <strong>MOYENNE GÉNÉRALE</strong>
                </td>
                <td style="text-align: center; font-size: 14px;">
                    <strong>{{ number_format($bulletin->moyenne_generale ?? 0, 2) }}/20</strong>
                </td>
                <td style="text-align: center;">
                    <strong>Rang : {{ $bulletin->rang ?? '-' }}/{{ $bulletin->total_classe ?? '-' }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Appréciation générale -->
    @if($bulletin->appreciation_generale)
        <div class="appreciation-section">
            <h3>📝 Appréciation Générale</h3>
            <p style="margin: 0; line-height: 1.6;">{{ $bulletin->appreciation_generale }}</p>
        </div>
    @endif

    <!-- Pied de page -->
    <div class="footer">
        <p>Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Ce document est certifié conforme par l'administration</p>
    </div>
</body>
</html>