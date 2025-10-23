<?php

namespace App\Exports;

use App\Models\Stagiaire;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StagiairesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function collection()
    {
        $user = User::findOrFail($this->userId);
        $filiereIds = $user->filieres->pluck('id')->toArray();

        return Stagiaire::with(['filiere', 'classe', 'niveau'])
            ->whereIn('filiere_id', $filiereIds)
            ->where('statut', 'actif')
            ->where('is_active', true)
            ->orderBy('nom')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prénom',
            'Email',
            'Téléphone',
            'Date de naissance',
            'Filière',
            'Classe',
            'Niveau',
            'Statut',
            'Date d\'inscription',
        ];
    }

    public function map($stagiaire): array
    {
        return [
            $stagiaire->matricule,
            $stagiaire->nom,
            $stagiaire->prenom,
            $stagiaire->email,
            $stagiaire->telephone ?? 'N/A',
            $stagiaire->date_naissance ? $stagiaire->date_naissance->format('d/m/Y') : 'N/A',
            $stagiaire->filiere->nom ?? 'N/A',
            $stagiaire->classe->nom ?? 'N/A',
            $stagiaire->niveau->nom ?? 'N/A',
            ucfirst($stagiaire->statut),
            $stagiaire->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}