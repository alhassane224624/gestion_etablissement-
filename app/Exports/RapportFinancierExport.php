<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RapportFinancierExport implements WithMultipleSheets
{
    protected $stats;
    protected $paiements;
    protected $echeanciers;

    public function __construct($stats, $paiements, $echeanciers)
    {
        $this->stats = $stats;
        $this->paiements = $paiements;
        $this->echeanciers = $echeanciers;
    }

    public function sheets(): array
    {
        return [
            new StatistiquesSheet($this->stats),
            new PaiementsSheet($this->paiements),
            new EcheanciersSheet($this->echeanciers),
            new AnalyseFilieresSheet($this->paiements),
            new AnalyseMethodesSheet($this->stats),
        ];
    }
}

// Feuille des statistiques
class StatistiquesSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $stats;

    public function __construct($stats)
    {
        $this->stats = $stats;
    }

    public function collection()
    {
        return collect([
            [
                'Indicateur' => 'Total Encaissé',
                'Valeur' => number_format($this->stats['total_encaisse'], 2) . ' DH',
                'Détails' => 'Évolution: ' . $this->stats['evolution_encaisse'] . '%'
            ],
            [
                'Indicateur' => 'Total Attendu',
                'Valeur' => number_format($this->stats['total_attendu'], 2) . ' DH',
                'Détails' => ''
            ],
            [
                'Indicateur' => 'Total Impayés',
                'Valeur' => number_format($this->stats['total_impayes'], 2) . ' DH',
                'Détails' => $this->stats['nb_retards'] . ' retards'
            ],
            [
                'Indicateur' => 'Taux de Recouvrement',
                'Valeur' => $this->stats['taux_recouvrement'] . '%',
                'Détails' => ''
            ],
            [
                'Indicateur' => 'Total Remises',
                'Valeur' => number_format($this->stats['total_remises'], 2) . ' DH',
                'Détails' => $this->stats['nb_remises'] . ' remises actives'
            ],
        ]);
    }

    public function headings(): array
    {
        return ['Indicateur', 'Valeur', 'Détails'];
    }

    public function title(): string
    {
        return 'Statistiques';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4e73df']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            'A:C' => [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E3E6F0']]
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
            'C' => 30,
        ];
    }
}

// Feuille des paiements
class PaiementsSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $paiements;

    public function __construct($paiements)
    {
        $this->paiements = $paiements;
    }

    public function collection()
    {
        return $this->paiements;
    }

    public function map($paiement): array
    {
        return [
            $paiement->id,
            \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i'),
            $paiement->stagiaire->nom . ' ' . $paiement->stagiaire->prenom,
            $paiement->stagiaire->filiere->nom,
            number_format($paiement->montant, 2),
            $paiement->methode_paiement,
            $paiement->reference ?? '-',
            $paiement->statut,
            $paiement->notes ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date Paiement',
            'Stagiaire',
            'Filière',
            'Montant (DH)',
            'Méthode',
            'Référence',
            'Statut',
            'Notes'
        ];
    }

    public function title(): string
    {
        return 'Paiements';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1cc88a']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 18,
            'C' => 25,
            'D' => 20,
            'E' => 15,
            'F' => 15,
            'G' => 20,
            'H' => 12,
            'I' => 30,
        ];
    }
}

// Feuille des échéanciers
class EcheanciersSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $echeanciers;

    public function __construct($echeanciers)
    {
        $this->echeanciers = $echeanciers;
    }

    public function collection()
    {
        return $this->echeanciers;
    }

    public function map($echeancier): array
    {
        return [
            $echeancier->id,
            \Carbon\Carbon::parse($echeancier->date_echeance)->format('d/m/Y'),
            $echeancier->stagiaire->nom . ' ' . $echeancier->stagiaire->prenom,
            $echeancier->stagiaire->filiere->nom,
            number_format($echeancier->montant, 2),
            number_format($echeancier->montant_paye, 2),
            number_format($echeancier->montant_restant, 2),
            $echeancier->statut,
            $echeancier->statut === 'en_retard' 
                ? \Carbon\Carbon::parse($echeancier->date_echeance)->diffInDays(now()) . ' jours'
                : '-'
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date Échéance',
            'Stagiaire',
            'Filière',
            'Montant (DH)',
            'Montant Payé (DH)',
            'Restant (DH)',
            'Statut',
            'Retard'
        ];
    }

    public function title(): string
    {
        return 'Échéanciers';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f6c23e']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 15,
            'C' => 25,
            'D' => 20,
            'E' => 15,
            'F' => 18,
            'G' => 15,
            'H' => 15,
            'I' => 12,
        ];
    }
}

// Feuille d'analyse par filières
class AnalyseFilieresSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $paiements;

    public function __construct($paiements)
    {
        $this->paiements = $paiements;
    }

    public function collection()
    {
        $parFiliere = $this->paiements->groupBy('stagiaire.filiere.nom');
        $totalGeneral = $this->paiements->sum('montant');

        $data = collect();

        foreach ($parFiliere as $nomFiliere => $paiementsFiliere) {
            $totalFiliere = $paiementsFiliere->sum('montant');
            $pourcentage = $totalGeneral > 0 ? ($totalFiliere / $totalGeneral) * 100 : 0;

            $data->push([
                'filiere' => $nomFiliere,
                'nb_paiements' => $paiementsFiliere->count(),
                'montant_total' => number_format($totalFiliere, 2),
                'pourcentage' => number_format($pourcentage, 2) . '%',
            ]);
        }

        // Ajouter ligne totale
        $data->push([
            'filiere' => 'TOTAL',
            'nb_paiements' => $this->paiements->count(),
            'montant_total' => number_format($totalGeneral, 2),
            'pourcentage' => '100%',
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'Filière',
            'Nombre de Paiements',
            'Montant Total (DH)',
            '% du Total'
        ];
    }

    public function title(): string
    {
        return 'Analyse Filières';
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->collection()->count() + 1;

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '36b9cc']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            $lastRow => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3E6F0']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 22,
            'C' => 20,
            'D' => 15,
        ];
    }
}

// Feuille d'analyse par méthodes
class AnalyseMethodesSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $stats;

    public function __construct($stats)
    {
        $this->stats = $stats;
    }

    public function collection()
    {
        $parMethode = $this->stats['par_methode'];
        $totalGeneral = array_sum($parMethode);

        $data = collect();

        foreach ($parMethode as $methode => $montant) {
            $pourcentage = $totalGeneral > 0 ? ($montant / $totalGeneral) * 100 : 0;

            $data->push([
                'methode' => $methode,
                'montant' => number_format($montant, 2),
                'pourcentage' => number_format($pourcentage, 2) . '%',
            ]);
        }

        // Ajouter ligne totale
        $data->push([
            'methode' => 'TOTAL',
            'montant' => number_format($totalGeneral, 2),
            'pourcentage' => '100%',
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'Méthode de Paiement',
            'Montant Total (DH)',
            '% du Total'
        ];
    }

    public function title(): string
    {
        return 'Analyse Méthodes';
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->collection()->count() + 1;

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '858796']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            $lastRow => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3E6F0']],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
            'C' => 15,
        ];
    }
}