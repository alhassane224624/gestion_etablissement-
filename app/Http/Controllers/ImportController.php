<?php

namespace App\Http\Controllers;

use App\Models\Stagiaire;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StagiairesImport;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function showImportForm()
    {
        $filieres = Filiere::all();
        return view('imports.stagiaires', compact('filieres'));
    }

    public function importStagiaires(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:2048',
            'filiere_id' => 'nullable|exists:filieres,id'
        ]);

        try {
            $import = new StagiairesImport($request->filiere_id);
            Excel::import($import, $request->file('file'));

            $results = $import->getResults();

            return redirect()->route('stagiaires.index')->with([
                'success' => "Import terminé! {$results['success']} stagiaires importés avec succès.",
                'import_errors' => $results['errors']
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename=template_stagiaires.csv'
        ];

        $template = "nom,prenom,matricule,filiere_nom\n";
        $template .= "DUPONT,Jean,ST001,Informatique\n";
        $template .= "MARTIN,Marie,ST002,Gestion\n";

        return response($template, 200, $headers);
    }
}

