<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Stagiaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// 🔔 AJOUT : Importer les notifications
use App\Notifications\AccountActivated;
use App\Notifications\AccountDeactivated;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'administrateur')->count(),
            'professeurs' => User::where('role', 'professeur')->count(),
            'stagiaires' => User::where('role', 'stagiaire')->count(),
            'actifs' => User::where('is_active', true)->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $matieres = Matiere::all();

        return view('users.create', compact('filieres', 'matieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:stagiaire,administrateur,professeur',
            'specialite' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'telephone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'filiere_id' => 'nullable|exists:filieres,id',
            'classe_id' => 'nullable|exists:classes,id',
            'niveau_id' => 'nullable|exists:niveaux,id',
            'filieres' => 'nullable|array',
            'filieres.*' => 'exists:filieres,id',
            'matieres' => 'nullable|array',
            'matieres.*' => 'exists:matieres,id',
        ]);

        $user = User::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'specialite' => $validated['specialite'] ?? null,
                'bio' => $validated['bio'] ?? null,
                'telephone' => $validated['telephone'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'created_by' => Auth::id(),
            ]
        );

        if ($user->role === 'stagiaire') {
            $parts = preg_split('/\s+/', trim($user->name));
            $nom = $parts[0] ?? $user->name;
            $prenom = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';

            $lastId = \App\Models\Stagiaire::max('id') + 1;
            $year = now()->format('Y');
            $matricule = sprintf("ST%s%05d", $year, $lastId);

            \App\Models\Stagiaire::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'matricule' => $matricule,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                    'statut' => 'actif',
                    'date_inscription' => now(),
                    'filiere_id' => $request->input('filiere_id'),
                    'classe_id' => $request->input('classe_id'),
                    'niveau_id' => $request->input('niveau_id'),
                    'created_by' => Auth::id(),
                ]
            );
        }

        if ($user->role === 'professeur') {
            if (!empty($validated['filieres'])) {
                $syncFilieres = [];
                foreach ($validated['filieres'] as $filiereId) {
                    $syncFilieres[$filiereId] = [
                        'created_by' => Auth::id(),
                        'is_active' => true,
                        'date_assignation' => now(),
                    ];
                }
                $user->filieres()->sync($syncFilieres);
            }

            if (!empty($validated['matieres'])) {
                $filiere_id = $validated['filieres'][0] ?? null;
                $syncMatieres = [];
                foreach ($validated['matieres'] as $matiereId) {
                    $syncMatieres[$matiereId] = [
                        'filiere_id' => $filiere_id,
                        'assigned_by' => Auth::id(),
                        'is_active' => true,
                        'date_assignation' => now(),
                    ];
                }
                $user->matieresEnseignees()->sync($syncMatieres);
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé ou mis à jour avec succès.');
    }

    public function show(User $user)
    {
        $user->load(['filieres', 'matieresEnseignees', 'createdBy', 'activatedBy']);

        $stats = [
            'notes_creees' => $user->notesCreated()->count(),
            'stagiaires_crees' => $user->stagiairesCreated()->count(),
            'plannings_crees' => $user->planningsCreated()->count(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        $user->load(['filieres', 'matieresEnseignees']);
        $filieres = Filiere::all();
        $matieres = Matiere::all();

        return view('users.edit', compact('user', 'filieres', 'matieres'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:stagiaire,administrateur,professeur',
            'specialite' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'telephone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'filieres' => 'nullable|array',
            'filieres.*' => 'exists:filieres,id',
            'matieres' => 'nullable|array',
            'matieres.*' => 'exists:matieres,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'specialite' => $validated['specialite'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'is_active' => $validated['is_active'] ?? $user->is_active,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($user->role === 'professeur') {
            $syncFilieres = [];
            if (!empty($validated['filieres'])) {
                foreach ($validated['filieres'] as $filiere_id) {
                    $syncFilieres[$filiere_id] = [
                        'created_by' => Auth::id(),
                        'is_active' => true,
                        'date_assignation' => now(),
                    ];
                }
            }
            $user->filieres()->sync($syncFilieres);

            $syncMatieres = [];
            if (!empty($validated['matieres'])) {
                $filiere_id = $validated['filieres'][0] ?? null;
                foreach ($validated['matieres'] as $matiere_id) {
                    $syncMatieres[$matiere_id] = [
                        'filiere_id' => $filiere_id,
                        'assigned_by' => Auth::id(),
                        'is_active' => true,
                        'date_assignation' => now(),
                    ];
                }
            }
            $user->matieresEnseignees()->sync($syncMatieres);
        }

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->role === 'stagiaire' && $user->stagiaire) {
            $user->stagiaire->delete();
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function toggleActive(User $user)
    {
        $newStatus = !$user->is_active;

        $user->update([
            'is_active' => $newStatus,
            'activated_at' => $newStatus ? now() : null,
            'activated_by' => $newStatus ? Auth::id() : null,
        ]);

        // 🔔 NOTIFICATION : Notifier l'utilisateur selon l'action
        if ($newStatus) {
            $user->notify(new AccountActivated());
        } else {
            $user->notify(new AccountDeactivated());
        }

        $status = $newStatus ? 'activé' : 'désactivé';

        return redirect()->back()
            ->with('success', "Utilisateur {$status} avec succès.");
    }
}