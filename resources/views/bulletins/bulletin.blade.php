<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin de {{ $stagiaire->nom }} {{ $stagiaire->prenom }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .etablissement {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .bulletin-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .annee-scolaire {
            font-size: 14px;
            color: #666;
        }
        
        .student-info {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .student-info td {
            padding: 5px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .student-info .label {
            font-weight: bold;
            color: #374151;
            width: 120px;
        }
        
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        .grades-table th {
            background-color: #1f2937;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }
        
        .grades-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: center;
        }
        
        .grades-table .matiere {
            text-align: left;
            font-weight: bold;
            background-color: #f9fafb;
        }
        
        .grade-excellent { background-color: #dcfce7; color: #16a34a; }
        .grade-bien { background-color: #dbeafe; color: #2563eb; }
        .grade-assez-bien { background-color: #fef3c7; color: #d97706; }
        .grade-passable { background-color: #f3f4f6; color: #6b7280; }
        .grade-insuffisant { background-color: #fee2e2; color: #dc2626; }
        
        .summary {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            background-color: #1f2937;
            color: white;
            margin: 10px -15px -15px;
            padding: 12px 15px;
        }
        
        .mention {
            text-align: center;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
        }
        
        .mention-excellent { background-color: #dcfce7; color: #16a34a; border: 2px solid #16a34a; }
        .mention-bien { background-color: #dbeafe; color: #2563eb; border: 2px solid #2563eb; }
        .mention-assez-bien { background-color: #fef3c7; color: #d97706; border: 2px solid #d97706; }
        .mention-passable { background-color: #f3f4f6; color: #6b7280; border: 2px solid #6b7280; }
        .mention-insuffisant { background-color: #fee2e2; color: #dc2626; border: 2px solid #dc2626; }
        
        .footer {
            margin-top: 40px;
            border-top: 2px solid #e2e8f0;
            padding-top: 20px;
        }
        
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-bottom: 1px solid #666;
            margin-top: 40px;
            margin-bottom: 5px;
        }
        
        .observations {
            margin-top: 20px;
            padding: 15px;
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0,0,0,0.05);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    {{-- <div class="watermark">BULLETIN OFFICIEL</div> --}}
    
    <!-- En-tête -->
    <div class="header">
        <div class="logo">🎓 GESTION SCOLAIRE</div>
        <div class="etablissement">Institut de Formation Professionnelle</div>
        <div class="bulletin-title">BULLETIN DE NOTES</div>
        <div class="annee-scolaire">Année Scolaire 2024-2025</div>
    </div>

    <!-- Informations de l'étudiant -->
    <div class="student-info">
        <table>
            <tr>
                <td class="label">Nom complet :</td>
                <td><strong>{{ strtoupper($stagiaire->nom) }} {{ ucfirst(strtolower($stagiaire->prenom)) }}</strong></td>
                <td class="label">Matricule :</td>
                <td><strong>{{ $stagiaire->matricule }}</strong></td>
            </tr>
            <tr>
                <td class="label">Filière :</td>
                <td>{{ $stagiaire->filiere->nom ?? 'N/A' }}</td>
                <td class="label">Niveau :</td>
                <td>{{ $stagiaire->filiere->niveau ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Classement :</td>
                <td><strong>{{ $rang }}{{ $rang == 1 ? 'er' : 'ème' }}</strong> / {{ $totalStagiaires }}</td>
                <td class="label">Date d'édition :</td>
                <td>{{ now()->format('d/m/Y à H:i') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tableau des notes -->
    <table class="grades-table">
        <thead>
            <tr>
                <th style="width: 30%;">MATIÈRES</th>
                <th style="width: 15%;">NOTE /20</th>
                <th style="width: 10%;">COEF.</th>
                <th style="width: 15%;">POINTS</th>
                <th style="width: 30%;">APPRÉCIATION</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPoints = 0;
                $totalCoefficients = 0;
                $hasNotes = false;
            @endphp
            
            @if($stagiaire->filiere && $stagiaire->filiere->matieres->count() > 0)
                @foreach($stagiaire->filiere->matieres as $matiereFiliere)
                    @php
                        $note = $stagiaire->notes->filter(function ($note) use ($matiereFiliere) {
                            return strtolower(trim($note->matiere)) === strtolower(trim($matiereFiliere->matiere));
                        })->sortByDesc('updated_at')->first();
                        
                        $noteValue = $note ? $note->note : null;
                        $points = $noteValue ? $noteValue * $matiereFiliere->coefficient : 0;
                        
                        if ($noteValue) {
                            $totalPoints += $points;
                            $totalCoefficients += $matiereFiliere->coefficient;
                            $hasNotes = true;
                        }
                        
                        // Classe CSS selon la note
                        $gradeClass = '';
                        $appreciation = '';
                        if ($noteValue) {
                            if ($noteValue >= 16) {
                                $gradeClass = 'grade-excellent';
                                $appreciation = 'Excellent travail';
                            } elseif ($noteValue >= 14) {
                                $gradeClass = 'grade-bien';
                                $appreciation = 'Bon travail';
                            } elseif ($noteValue >= 12) {
                                $gradeClass = 'grade-assez-bien';
                                $appreciation = 'Travail satisfaisant';
                            } elseif ($noteValue >= 10) {
                                $gradeClass = 'grade-passable';
                                $appreciation = 'Travail passable';
                            } else {
                                $gradeClass = 'grade-insuffisant';
                                $appreciation = 'Travail insuffisant';
                            }
                        }
                    @endphp
                    
                    <tr>
                        <td class="matiere">{{ $matiereFiliere->matiere }}</td>
                        <td class="{{ $gradeClass }}">
                            {{ $noteValue ? number_format($noteValue, 2) : 'N.N.' }}
                        </td>
                        <td>{{ $matiereFiliere->coefficient }}</td>
                        <td class="{{ $gradeClass }}">
                            {{ $noteValue ? number_format($points, 2) : '-' }}
                        </td>
                        <td style="text-align: left; font-style: italic; color: #666;">
                            {{ $noteValue ? $appreciation : 'Non noté' }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center; color: #666; font-style: italic;">
                        Aucune matière configurée pour cette filière
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Résumé des résultats -->
    <div class="summary">
        <div class="summary-row">
            <span><strong>Total Points Obtenus :</strong></span>
            <span>{{ number_format($totalPoints, 2) }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Total Coefficients :</strong></span>
            <span>{{ $totalCoefficients }}</span>
        </div>
        <div class="summary-row">
            <span><strong>MOYENNE GÉNÉRALE :</strong></span>
            <span>{{ number_format($moyenne, 2) }}/20</span>
        </div>
    </div>

    <!-- Mention et statut -->
    @php
        $mentionClass = '';
        $mentionText = '';
        $statut = '';
        
        if ($moyenne >= 16) {
            $mentionClass = 'mention-excellent';
            $mentionText = 'TRÈS BIEN';
            $statut = 'ADMIS(E)';
        } elseif ($moyenne >= 14) {
            $mentionClass = 'mention-bien';
            $mentionText = 'BIEN';
            $statut = 'ADMIS(E)';
        } elseif ($moyenne >= 12) {
            $mentionClass = 'mention-assez-bien';
            $mentionText = 'ASSEZ BIEN';
            $statut = 'ADMIS(E)';
        } elseif ($moyenne >= 10) {
            $mentionClass = 'mention-passable';
            $mentionText = 'PASSABLE';
            $statut = 'ADMIS(E)';
        } else {
            $mentionClass = 'mention-insuffisant';
            $mentionText = 'INSUFFISANT';
            $statut = 'AJOURNÉ(E)';
        }
    @endphp

    <div class="mention {{ $mentionClass }}">
        {{ $statut }} - MENTION {{ $mentionText }}
        <br>
        <small style="font-weight: normal; opacity: 0.8;">
            Classé(e) {{ $rang }}{{ $rang == 1 ? 'er' : 'ème' }} sur {{ $totalStagiaires }} stagiaires
        </small>
    </div>

    <!-- Observations -->
    <div class="observations">
        <strong>📝 Observations du conseil de classe :</strong>
        <br><br>
        @if($moyenne >= 16)
            Excellent parcours. L'étudiant(e) fait preuve d'un niveau exceptionnel et d'un travail remarquable. Félicitations !
        @elseif($moyenne >= 14)
            Très bon travail. L'étudiant(e) maîtrise bien les compétences attendues. Encouragements à poursuivre ainsi.
        @elseif($moyenne >= 12)
            Travail satisfaisant. L'étudiant(e) peut encore améliorer ses résultats avec plus d'efforts.
        @elseif($moyenne >= 10)
            Résultats en limite d'admission. L'étudiant(e) doit redoubler d'efforts pour consolider ses acquis.
        @else
            Résultats insuffisants. L'étudiant(e) doit fournir un travail considérable pour rattraper son retard.
            Un suivi particulier est recommandé.
        @endif
        
        @if(!$hasNotes)
            <br><br><em>⚠️ Attention : Ce bulletin contient des matières non évaluées. 
            Veuillez vous rapprocher du secrétariat pédagogique.</em>
        @endif
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <strong>Période d'évaluation :</strong> Semestre 1 - 2024/2025
            </div>
            <div>
                <strong>Document généré le :</strong> {{ now()->format('d/m/Y à H:i:s') }}
            </div>
        </div>
        
        <div class="signatures">
            <div class="signature-box">
                <div><strong>Le Directeur des Études</strong></div>
                <div class="signature-line"></div>
                <div style="font-size: 10px; color: #666;">Signature et cachet</div>
            </div>
            
            <div class="signature-box">
                <div><strong>L'Étudiant(e)</strong></div>
                <div class="signature-line"></div>
                <div style="font-size: 10px; color: #666;">Signature</div>
            </div>
            
            <div class="signature-box">
                <div><strong>Le Responsable légal</strong></div>
                <div class="signature-line"></div>
                <div style="font-size: 10px; color: #666;">Signature</div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #999;">
            Ce bulletin est un document officiel. Toute falsification est passible de sanctions disciplinaires.
        </div>
    </div>
</body>
</html>