<?php

namespace App\Exports;

use App\Models\Note;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NotesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $professeurId;
    protected $matiereId;

    public function __construct($professeurId, $matiereId = null)
    {
        $this->professeurId = $professeurId;
        $this->matiereId = $matiereId;
    }

    /**
     * Récupérer les données
     */
    public function collection()
    {
        $professeur = User::findOrFail($this->professeurId);
        $filiereIds = $professeur->filieres->pluck('id')->toArray();

        $query = Note::with(['stagiaire.filiere', 'stagiaire.classe', 'matiere', 'creator'])
            ->where('created_by', $this->professeurId)
            ->whereHas('stagiaire', function ($q) use ($filiereIds) {
                $q->whereIn('filiere_id', $filiereIds);
            });

        if ($this->matiereId) {
            $query->where('matiere_id', $this->matiereId);
        }

        return $query->latest()->get();
    }

    /**
     * En-têtes des colonnes
     */
    public function headings(): array
    {
        return [
            '#',
            'Matricule',
            'Nom',
            'Prénom',
            'Filière',
            'Classe',
            'Matière',
            'Type de Note',
            'Note',
            'Note sur',
            'Note/20',
            'Commentaire',
            'Créée le',
            'Créée par',
        ];
    }

    /**
     * Mapper les données
     */
    public function map($note): array
    {
        static $counter = 0;
        $counter++;

        return [
            $counter,
            $note->stagiaire->matricule,
            $note->stagiaire->nom,
            $note->stagiaire->prenom,
            $note->stagiaire->filiere->nom ?? 'N/A',
            $note->stagiaire->classe->nom ?? 'N/A',
            $note->matiere->nom ?? 'N/A',
            strtoupper($note->type_note),
            number_format($note->note, 2),
            $note->note_sur ?? 20,
            number_format(($note->note / ($note->note_sur ?? 20)) * 20, 2),
            $note->commentaire ?? '',
            $note->created_at->format('d/m/Y H:i'),
            $note->creator->name ?? 'N/A',
        ];
    }

    /**
     * Styles du tableau
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    /**
     * Titre de la feuille
     */
    public function title(): string
    {
        return 'Notes Export';
    }
}