<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StagiaireController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\StagiaireSpaceController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AnneeScolaireController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\EcheancierController;
use App\Http\Controllers\RemiseController;
use App\Http\Controllers\RapportFinancierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__ . '/auth.php';

// ============================================================================
// ROUTES PUBLIQUES
// ============================================================================

Route::get('/inscription-stagiaire', [StagiaireController::class, 'showInscriptionForm'])
    ->name('stagiaires.inscription.form');
Route::post('/inscription-stagiaire', [StagiaireController::class, 'storeInscription'])
    ->name('stagiaires.inscription.store');

// ============================================================================
// ROUTES AUTHENTIFIÉES
// ============================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Redirection intelligente selon le rôle
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        switch ($user->role) {
            case 'administrateur':
                return redirect('/admin/dashboard');
            case 'professeur':
                return redirect('/professeur/dashboard');
            case 'stagiaire':
                return redirect('/stagiaire/dashboard');
            default:
                return redirect()->route('login')->with('error', 'Rôle non reconnu');
        }
    })->name('dashboard');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============================================================================
    // MESSAGERIE ET NOTIFICATIONS
    // ============================================================================

   Route::prefix('messages')->name('messages.')->group(function () {
    Route::get('/', [MessageController::class, 'index'])->name('index');
    Route::get('/create', [MessageController::class, 'create'])->name('create');
    Route::post('/send', [MessageController::class, 'sendById'])->name('send.by-id');
    Route::post('/send/{user}', [MessageController::class, 'store'])->name('send');
    Route::get('/conversation/{user}', [MessageController::class, 'conversation'])->name('conversation');
    Route::get('/send-group', [MessageController::class, 'showSendGroupForm'])->name('send-group.form');
    Route::post('/send-group', [MessageController::class, 'sendGroup'])->name('send-group');
    Route::get('/unread-count', [MessageController::class, 'unreadCount'])->name('unread-count');
    Route::delete('/{user}/delete', [MessageController::class, 'deleteConversation'])->name('delete');
    Route::post('/bulk-delete', [MessageController::class, 'bulkDelete'])->name('bulk-delete');
    Route::post('/reset', [MessageController::class, 'reset'])->name('reset'); // ✅ Correction ici
});


    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/read/all', [NotificationController::class, 'deleteRead'])->name('delete-read');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/recent', [NotificationController::class, 'getRecent'])->name('recent');
    });
});

// ============================================================================
// ROUTES STAGIAIRE
// ============================================================================

Route::middleware(['auth', 'verified', 'stagiaire'])->prefix('stagiaire')->name('stagiaire.')->group(function () {
    Route::get('/dashboard', [StagiaireSpaceController::class, 'dashboard'])->name('dashboard');
    Route::get('/notes', [StagiaireSpaceController::class, 'mesNotes'])->name('notes');
    Route::get('/notes/telecharger', [StagiaireSpaceController::class, 'telechargerReleve'])->name('notes.telecharger');
    Route::get('/bulletin', [StagiaireSpaceController::class, 'monBulletin'])->name('bulletin');
    Route::get('/bulletin/{bulletin}/telecharger', [StagiaireSpaceController::class, 'telechargerBulletin'])->name('bulletin.telecharger');
    Route::get('/emploi-du-temps', [StagiaireSpaceController::class, 'emploiDuTemps'])->name('emploi-du-temps');
    Route::get('/absences', [StagiaireSpaceController::class, 'mesAbsences'])->name('absences');
    Route::get('/profil', [StagiaireSpaceController::class, 'monProfil'])->name('profil');
    
    // 💰 PAIEMENTS STAGIAIRE - ✅ CORRECTION APPLIQUÉE
    Route::get('/mes-paiements', [PaiementController::class, 'mesPaiements'])->name('paiements');
    Route::get('/mes-echeanciers', [EcheancierController::class, 'mesEcheanciers'])->name('echeanciers');
    Route::get('/paiement/{paiement}/recu', [PaiementController::class, 'telechargerRecu'])->name('paiement.recu');
});

// ============================================================================
// ROUTES PROFESSEUR
// ============================================================================

Route::middleware(['auth', 'verified', 'professeur'])->prefix('professeur')->name('professeur.')->group(function () {
    Route::get('/dashboard', [ProfesseurController::class, 'dashboard'])->name('dashboard');
    Route::get('/stagiaires', [ProfesseurController::class, 'index'])->name('stagiaires');
    Route::get('/stagiaires/{stagiaire}/notes', [ProfesseurController::class, 'showNotes'])->name('stagiaires.notes');
    Route::post('/stagiaires/{stagiaire}/notes', [ProfesseurController::class, 'storeNote'])->name('stagiaires.notes.store');
    Route::put('/stagiaires/{stagiaire}/notes/{note}', [ProfesseurController::class, 'updateNote'])->name('stagiaires.notes.update');
    Route::get('/notes-par-matiere', [ProfesseurController::class, 'notesParMatiere'])->name('notes-par-matiere');
    Route::get('/notes/export-pdf', [ProfesseurController::class, 'exportNotesPdf'])->name('notes.export-pdf');
    Route::get('/notes/export-excel', [ProfesseurController::class, 'exportNotesExcel'])->name('notes.export-excel');
    Route::get('/stagiaires/export-pdf', [ProfesseurController::class, 'exportStagiairesPdf'])->name('stagiaires.export-pdf');
    Route::get('/stagiaires/export-excel', [ProfesseurController::class, 'exportStagiairesExcel'])->name('stagiaires.export-excel');
    Route::get('/presences', [ProfesseurController::class, 'presences'])->name('presences');
    Route::post('/presences/marquer', [ProfesseurController::class, 'marquerAbsence'])->name('presences.marquer');
    Route::delete('/presences/{absence}', [ProfesseurController::class, 'supprimerAbsence'])->name('presences.supprimer');
    Route::get('/planning', [ProfesseurController::class, 'monPlanning'])->name('planning');
    Route::get('/planning/create', [ProfesseurController::class, 'createPlanning'])->name('planning.create');
    Route::post('/planning', [ProfesseurController::class, 'storePlanning'])->name('planning.store');
});

// ============================================================================
// ROUTES ADMINISTRATEUR
// ============================================================================

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // ========================================================================
    // 📊 RAPPORTS FINANCIERS
    // ========================================================================
    Route::prefix('admin/rapports')->name('admin.rapports.')->group(function () {
        Route::get('/financier', [RapportFinancierController::class, 'index'])->name('financier');
        Route::get('/financier/export', [RapportFinancierController::class, 'exporter'])->name('financier.export');
        Route::get('/financier/graphique', [RapportFinancierController::class, 'donneesGraphique'])->name('financier.graphique');
    });
    
    // Stagiaires
    Route::resource('stagiaires', StagiaireController::class);
    Route::post('stagiaires/{stagiaire}/change-statut', [StagiaireController::class, 'changeStatut'])->name('stagiaires.change-statut');
    Route::get('stagiaires-export', [StagiaireController::class, 'export'])->name('stagiaires.export');
    
    // Filières, Classes, Niveaux, Matières
    Route::resource('filieres', FiliereController::class);
    Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe']);
    Route::resource('niveaux', NiveauController::class);
    Route::resource('matieres', MatiereController::class);
    
    // Notes
    Route::resource('notes', NoteController::class);
    Route::get('/notes/export', [NoteController::class, 'export'])->name('notes.export');
    Route::get('stagiaires/{stagiaire}/releve', [NoteController::class, 'releveStagiaire'])->name('notes.releve');
    
    // Salles
    Route::resource('salles', SalleController::class);
    Route::patch('salles/{salle}/toggle-disponibilite', [SalleController::class, 'toggleDisponibilite'])->name('salles.toggle-disponibilite');
    Route::get('salles/{salle}/planning', [SalleController::class, 'planning'])->name('salles.planning');
    Route::post('salles/check-disponibilite', [SalleController::class, 'checkDisponibilite'])->name('salles.check-disponibilite');
    
    // Planning
    Route::resource('planning', PlanningController::class);
    Route::post('planning/{planning}/valider', [PlanningController::class, 'valider'])->name('planning.valider');
    Route::post('planning/{planning}/annuler', [PlanningController::class, 'annuler'])->name('planning.annuler');
    
    // Absences
    Route::resource('absences', AbsenceController::class);
    Route::get('absences-rapport', [AbsenceController::class, 'rapportAbsences'])->name('absences.rapport');
    Route::get('absences-export', [AbsenceController::class, 'exportAbsences'])->name('absences.export');
    
    // Années scolaires
    Route::resource('annees-scolaires', AnneeScolaireController::class);
    Route::post('annees-scolaires/{annees_scolaire}/activate', [AnneeScolaireController::class, 'activate'])->name('annees-scolaires.activate');
    Route::post('annees-scolaires/{annees_scolaire}/duplicate', [AnneeScolaireController::class, 'duplicate'])->name('annees-scolaires.duplicate');
    Route::get('annees-scolaires-active', [AnneeScolaireController::class, 'getActive'])->name('annees-scolaires.active');
    
    // Périodes
    Route::resource('periodes', PeriodeController::class);
    Route::post('periodes/{periode}/activer', [PeriodeController::class, 'activerPeriode'])->name('periodes.activer');
    
    // Bulletins
    Route::resource('bulletins', BulletinController::class)->only(['index', 'show']);
    Route::post('bulletins/generate', [BulletinController::class, 'generate'])->name('bulletins.generate');
    Route::get('bulletins/{bulletin}/download-pdf', [BulletinController::class, 'downloadPdf'])->name('bulletins.download-pdf');
    Route::patch('bulletins/{bulletin}/validate', [BulletinController::class, 'validateBulletin'])->name('bulletins.validate');
    
    // Users
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    
    // Professeurs
    Route::get('professeurs/{professeur}/filieres', [ProfesseurController::class, 'editFilieres'])->name('professeurs.filieres.edit');
    Route::put('professeurs/{professeur}/filieres', [ProfesseurController::class, 'updateFilieres'])->name('professeurs.filieres.update');
    Route::get('professeurs/{professeur}/matieres', [ProfesseurController::class, 'editMatieres'])->name('professeurs.matieres.edit');
    Route::put('professeurs/{professeur}/matieres', [ProfesseurController::class, 'updateMatieres'])->name('professeurs.matieres.update');
    
    // Import
    Route::get('import/stagiaires', [ImportController::class, 'showImportForm'])->name('import.stagiaires.form');
    Route::post('import/stagiaires', [ImportController::class, 'importStagiaires'])->name('import.stagiaires');
    Route::get('import/template', [ImportController::class, 'downloadTemplate'])->name('import.template');
    
    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/export', [StatisticsController::class, 'export'])->name('statistics.export');
    
    // ============================================================================
    // 💳 SYSTÈME DE PAIEMENT COMPLET - ✅ ROUTES CORRIGÉES
    // ============================================================================
    
    // Paiements
    Route::prefix('paiements')->name('paiements.')->group(function () {
        Route::get('/', [PaiementController::class, 'index'])->name('index');
        Route::get('/create', [PaiementController::class, 'create'])->name('create');
        Route::post('/', [PaiementController::class, 'store'])->name('store');
        Route::get('/{paiement}', [PaiementController::class, 'show'])->name('show');
        Route::post('/{paiement}/valider', [PaiementController::class, 'valider'])->name('valider');
        Route::post('/{paiement}/refuser', [PaiementController::class, 'refuser'])->name('refuser');
        // ✅ CORRECTION: Utilisation de telechargerRecu au lieu de recu
        Route::get('/{paiement}/recu', [PaiementController::class, 'telechargerRecu'])->name('recu');
        Route::get('/stagiaire/{stagiaire}/historique', [PaiementController::class, 'historique'])->name('historique');
    });

    // Échéanciers
    Route::prefix('echeanciers')->name('echeanciers.')->group(function () {
        Route::get('/', [EcheancierController::class, 'index'])->name('index');
        Route::get('/create', [EcheancierController::class, 'create'])->name('create');
        Route::post('/', [EcheancierController::class, 'store'])->name('store');
        Route::get('/{echeancier}', [EcheancierController::class, 'show'])->name('show');
        Route::get('/{echeancier}/edit', [EcheancierController::class, 'edit'])->name('edit');
        Route::put('/{echeancier}', [EcheancierController::class, 'update'])->name('update');
        Route::delete('/{echeancier}', [EcheancierController::class, 'destroy'])->name('destroy');
        
        // Actions spéciales
        Route::get('/generer', [EcheancierController::class, 'create'])->name('generer');
        Route::post('/generer-mensuels', [EcheancierController::class, 'genererMensuels'])->name('generer-mensuels');
        Route::post('/verifier-retards', [EcheancierController::class, 'verifierRetards'])->name('verifier-retards');
        Route::get('/{echeancier}/print', [EcheancierController::class, 'imprimer'])->name('print');
    });

    // Remises
    Route::resource('remises', RemiseController::class);
    Route::patch('remises/{remise}/toggle', [RemiseController::class, 'toggleActive'])->name('remises.toggle');
});