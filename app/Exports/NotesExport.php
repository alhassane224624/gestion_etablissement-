<?php

namespace App\Exports;

use App\Models\Note;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NotesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $userId;
    protected $matiereId;

    public function __construct($userId, $matiereId = null)
    {
        $this->userId = $userId;
        $this->matiereId = $matiereId;
    }

    public function collection()
    {
        $query = Note::with(['stagiaire.filiere', 'stagiaire.classe', 'matiere', 'periode'])
            ->where('created_by', $this->userId);

        if ($this->matiereId) {
            $query->where('matiere_id', $this->matiereId);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Stagiaire',
            'Filière',
            'Classe',
            'Matière',
            'Note',
            'Note sur',
            'Type',
            'Période',
            'Commentaire',
            'Date',
        ];
    }

    public function map($note): array
    {
        return [
            $note->stagiaire->matricule ?? 'N/A',
            $note->stagiaire->nom . ' ' . $note->stagiaire->prenom,
            $note->stagiaire->filiere->nom ?? 'N/A',
            $note->stagiaire->classe->nom ?? 'N/A',
            $note->matiere->nom ?? 'N/A',
            $note->note,
            $note->note_sur ?? 20,
            strtoupper($note->type_note),
            $note->periode->nom ?? 'N/A',
            $note->commentaire ?? '',
            $note->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}