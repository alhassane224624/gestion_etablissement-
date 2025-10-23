<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export des Notes</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        .header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4f46e5;
        }
        .info {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th {
            background-color: #4f46e5;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }
        td {
            padding: 6px;
            text-align: center;
        }
        .footer {
            text-align: right;
            font-size: 11px;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }
        .note-excellente { background-color: #d1fae5; }
        .note-bonne { background-color: #dbeafe; }
        .note-moyenne { background-color: #fef3c7; }
        .note-faible { background-color: #fee2e2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>📚 Export des Notes</h2>
        <h3>Professeur: {{ $user->name }}</h3>
    </div>

    <div class="info">
        @if($matiere)
            <p><strong>Matière:</strong> {{ $matiere->nom }} ({{ $matiere->code }})</p>
        @else
            <p><strong>Matière:</strong> Toutes les matières</p>
        @endif
        <p><strong>Date d'export:</strong> {{ now()->format('d/m/Y à H:i') }}</p>
        <p><strong>Nombre total de notes:</strong> {{ $notes->count() }}</p>
        @if($notes->count() > 0)
            <p><strong>Moyenne générale:</strong> {{ number_format($notes->avg('note'), 2) }}/20</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Stagiaire</th>
                <th style="width: 20%;">Filière</th>
                <th style="width: 20%;">Matière</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 10%;">Note</th>
                <th style="width: 10%;">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($notes as $index => $note)
                @php
                    $noteClass = '';
                    if ($note->note >= 16) $noteClass = 'note-excellente';
                    elseif ($note->note >= 14) $noteClass = 'note-bonne';
                    elseif ($note->note >= 10) $noteClass = 'note-moyenne';
                    else $noteClass = 'note-faible';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left;">{{ $note->stagiaire->nom }} {{ $note->stagiaire->prenom }}</td>
                    <td>{{ $note->stagiaire->filiere->nom }}</td>
                    <td>{{ $note->matiere->nom }}</td>
                    <td>{{ strtoupper($note->type_note) }}</td>
                    <td class="{{ $noteClass }}"><strong>{{ number_format($note->note, 2) }}</strong>/{{ $note->note_sur ?? 20 }}</td>
                    <td>{{ $note->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Aucune note trouvée
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($notes->count() > 0)
        <div style="margin-top: 20px; padding: 10px; background-color: #f3f4f6; border-radius: 5px;">
            <p><strong>Statistiques:</strong></p>
            <ul style="margin: 5px 0;">
                <li>Nombre de notes: {{ $notes->count() }}</li>
                <li>Moyenne générale: {{ number_format($notes->avg('note'), 2) }}/20</li>
                <li>Note la plus élevée: {{ number_format($notes->max('note'), 2) }}/20</li>
                <li>Note la plus basse: {{ number_format($notes->min('note'), 2) }}/20</li>
                <li>Taux de réussite (≥10): {{ $notes->count() > 0 ? number_format(($notes->where('note', '>=', 10)->count() / $notes->count()) * 100, 1) : 0 }}%</li>
            </ul>
        </div>
    @endif

    <div class="footer">
        <p><strong>Document généré le:</strong> {{ now()->format('d/m/Y à H:i') }}</p>
        <p><em>Ce document est confidentiel</em></p>
    </div>
</body>
</html>