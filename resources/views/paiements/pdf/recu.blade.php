<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement - {{ $paiement->numero_transaction }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #4f46e5;
            font-size: 24pt;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 10pt;
        }
        
        .recu-title {
            text-align: center;
            background-color: #4f46e5;
            color: white;
            padding: 15px;
            margin-bottom: 30px;
            font-size: 18pt;
            font-weight: bold;
        }
        
        .info-section {
            margin-bottom: 30px;
        }
        
        .info-section h3 {
            background-color: #f3f4f6;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #4f46e5;
            font-size: 13pt;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            padding: 8px;
            font-weight: bold;
            width: 40%;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-value {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .montant-principal {
            text-align: center;
            margin: 40px 0;
            padding: 20px;
            background-color: #f0fdf4;
            border: 2px solid #22c55e;
            border-radius: 8px;
        }
        
        .montant-principal .label {
            font-size: 12pt;
            color: #666;
            margin-bottom: 10px;
        }
        
        .montant-principal .montant {
            font-size: 32pt;
            font-weight: bold;
            color: #22c55e;
        }
        
        .montant-principal .devise {
            font-size: 16pt;
            color: #666;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        
        .signature-section {
            margin-top: 60px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
            font-size: 10pt;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100pt;
            color: rgba(34, 197, 94, 0.1);
            font-weight: bold;
            z-index: -1;
        }
        
        .qr-code {
            text-align: center;
            margin: 30px 0;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body>
    <div class="watermark">PAYÉ</div>
    
    <div class="container">
        {{-- En-tête --}}
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>{{ \App\Models\ConfigurationPaiement::get('etablissement_adresse') }}</p>
            <p>Tél: {{ \App\Models\ConfigurationPaiement::get('etablissement_telephone') }} | Email: {{ \App\Models\ConfigurationPaiement::get('etablissement_email') }}</p>
        </div>

        {{-- Titre --}}
        <div class="recu-title">
            REÇU DE PAIEMENT
        </div>

        {{-- Numéro et statut --}}
        <div style="text-align: center; margin-bottom: 30px;">
            <p style="font-size: 14pt;">
                <strong>N° Transaction:</strong> 
                <span style="color: #4f46e5;">{{ $paiement->numero_transaction }}</span>
            </p>
            <p style="margin-top: 10px;">
                <span class="badge badge-success">✓ VALIDÉ</span>
            </p>
        </div>

        {{-- Informations stagiaire --}}
        <div class="info-section">
            <h3>INFORMATIONS STAGIAIRE</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nom complet</div>
                    <div class="info-value">{{ $paiement->stagiaire->nom_complet }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Matricule</div>
                    <div class="info-value">{{ $paiement->stagiaire->matricule }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Filière</div>
                    <div class="info-value">{{ $paiement->stagiaire->filiere->nom ?? 'N/A' }}</div>
                </div>
                @if($paiement->stagiaire->classe)
                <div class="info-row">
                    <div class="info-label">Classe</div>
                    <div class="info-value">{{ $paiement->stagiaire->classe->nom }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Détails du paiement --}}
        <div class="info-section">
            <h3>DÉTAILS DU PAIEMENT</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Date de paiement</div>
                    <div class="info-value">{{ $paiement->date_paiement->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date de validation</div>
                    <div class="info-value">{{ $paiement->valide_at ? $paiement->valide_at->format('d/m/Y à H:i') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Type de paiement</div>
                    <div class="info-value">{{ $paiement->type_libelle }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Méthode de paiement</div>
                    <div class="info-value">{{ $paiement->methode_libelle }}</div>
                </div>
                @if($paiement->description)
                <div class="info-row">
                    <div class="info-label">Description</div>
                    <div class="info-value">{{ $paiement->description }}</div>
                </div>
                @endif
                @if($paiement->reference_externe)
                <div class="info-row">
                    <div class="info-label">Référence externe</div>
                    <div class="info-value">{{ $paiement->reference_externe }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Montant principal --}}
        <div class="montant-principal">
            <div class="label">MONTANT PAYÉ</div>
            <div>
                <span class="montant">{{ number_format($paiement->montant, 2) }}</span>
                <span class="devise">DH</span>
            </div>
            <div style="margin-top: 10px; font-size: 10pt; color: #666;">
                ({{ \App\Helpers\NumberHelper::convertirEnLettres($paiement->montant) }} dirhams)
            </div>
        </div>

        {{-- Échéanciers affectés --}}
        @if($paiement->echeanciers->count() > 0)
        <div class="info-section">
            <h3>ÉCHÉANCIERS AFFECTÉS</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f3f4f6;">
                        <th style="padding: 8px; text-align: left; border: 1px solid #e5e7eb;">Titre</th>
                        <th style="padding: 8px; text-align: right; border: 1px solid #e5e7eb;">Montant affecté</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paiement->echeanciers as $echeancier)
                    <tr>
                        <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ $echeancier->titre }}</td>
                        <td style="padding: 8px; text-align: right; border: 1px solid #e5e7eb;">
                            {{ number_format($echeancier->pivot->montant_affecte, 2) }} DH
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Signatures --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    Signature du stagiaire
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    Cachet et signature de l'établissement
                </div>
            </div>
        </div>

        {{-- Pied de page --}}
        <div class="footer">
            <p style="margin-bottom: 10px;">
                <strong>{{ \App\Models\ConfigurationPaiement::get('recu_footer_text') }}</strong>
            </p>
            <p style="font-size: 8pt; color: #999;">
                Document généré le {{ now()->format('d/m/Y à H:i') }}
            </p>
            <p style="font-size: 8pt; color: #999; margin-top: 5px;">
                {{ \App\Models\ConfigurationPaiement::get('recu_conditions') }}
            </p>
        </div>
    </div>
</body>
</html>