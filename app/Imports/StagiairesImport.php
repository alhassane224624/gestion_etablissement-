<?php
// App\Imports\StagiairesImport.php
namespace App\Imports;

use App\Models\Stagiaire;
use App\Models\Filiere;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;

class StagiairesImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $filiereId;
    protected $results = ['success' => 0, 'errors' => []];

    public function __construct($filiereId = null)
    {
        $this->filiereId = $filiereId;
    }

    public function model(array $row)
    {
        try {
            // Déterminer la filière
            $filiereId = $this->filiereId;
            
            if (!$filiereId && isset($row['filiere_nom'])) {
                $filiere = Filiere::where('nom', 'LIKE', '%' . $row['filiere_nom'] . '%')->first();
                $filiereId = $filiere ? $filiere->id : null;
            }

            if (!$filiereId) {
                $this->results['errors'][] = "Ligne {$row['nom']} {$row['prenom']}: Filière non trouvée";
                return null;
            }

            // Vérifier si le matricule existe déjà
            if (Stagiaire::where('matricule', $row['matricule'])->exists()) {
                $this->results['errors'][] = "Matricule {$row['matricule']} existe déjà";
                return null;
            }

            $stagiaire = new Stagiaire([
                'nom' => strtoupper($row['nom']),
                'prenom' => ucwords(strtolower($row['prenom'])),
                'matricule' => $row['matricule'],
                'filiere_id' => $filiereId,
            ]);

            $this->results['success']++;
            return $stagiaire;

        } catch (\Exception $e) {
            $this->results['errors'][] = "Erreur ligne {$row['nom']}: " . $e->getMessage();
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|unique:stagiaires,matricule',
        ];
    }

    public function getResults()
    {
        return $this->results;
    }
}