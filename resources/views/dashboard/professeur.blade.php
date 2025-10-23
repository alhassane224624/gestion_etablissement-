@extends('layouts.app-professeur')

@section('title', 'Dashboard Professeur')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-chalkboard-teacher text-purple-600"></i>
            Dashboard Professeur
        </h1>
        <p class="text-gray-600 mt-2">Bienvenue, {{ auth()->user()->name }}</p>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Mes Filières - ✅ AJOUT DU LIEN CLIQUABLE -->
        <a href="{{ route('professeur.stagiaires') }}" class="block transform hover:scale-105 transition-transform duration-200">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white h-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm uppercase tracking-wide">Mes Filières</p>
                        <p class="text-3xl font-bold mt-2">{{ $data['filieres']->count() }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-4">
                        <i class="fas fa-book text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-purple-100">
                    Voir les filières <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>

        <!-- Mes Matières - ✅ AJOUT DU LIEN CLIQUABLE -->
        <a href="{{ route('professeur.notes-par-matiere') }}" class="block transform hover:scale-105 transition-transform duration-200">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white h-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm uppercase tracking-wide">Mes Matières</p>
                        <p class="text-3xl font-bold mt-2">{{ $data['matieres']->count() }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-4">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-blue-100">
                    Voir mes matières <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>

        <!-- Mes Stagiaires - ✅ CORRECTION DU LIEN -->
        <a href="{{ route('professeur.stagiaires') }}" class="block transform hover:scale-105 transition-transform duration-200">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white h-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm uppercase tracking-wide">Mes Stagiaires</p>
                        <p class="text-3xl font-bold mt-2">{{ $data['total_stagiaires'] }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-4">
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-green-100">
                    Voir tous les stagiaires <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>

        <!-- Notes Saisies - ✅ CORRECTION DU LIEN -->
        <a href="{{ route('professeur.notes-par-matiere') }}" class="block transform hover:scale-105 transition-transform duration-200">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white h-full">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm uppercase tracking-wide">Notes Saisies</p>
                        <p class="text-3xl font-bold mt-2">{{ $data['total_notes'] }}</p>
                    </div>
                    <div class="bg-white bg-opacity-30 rounded-full p-4">
                        <i class="fas fa-clipboard-list text-3xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-orange-100">
                    Voir toutes les notes <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Planning du jour -->
    @if($data['planning_aujourd_hui']->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-calendar-day text-blue-600"></i>
                Mon Planning du Jour - {{ now()->format('d/m/Y') }}
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($data['planning_aujourd_hui'] as $cours)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border-l-4 
                        @if($cours->statut == 'valide') border-green-500
                        @elseif($cours->statut == 'en_cours') border-blue-500
                        @elseif($cours->statut == 'termine') border-gray-500
                        @elseif($cours->statut == 'annule') border-red-500
                        @else border-yellow-500
                        @endif">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 rounded-lg px-3 py-2 text-center min-w-[80px]">
                                <p class="text-xs text-blue-600 font-semibold">{{ \Carbon\Carbon::parse($cours->heure_debut)->format('H:i') }}</p>
                                <p class="text-xs text-blue-400">{{ \Carbon\Carbon::parse($cours->heure_fin)->format('H:i') }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $cours->matiere->nom ?? 'Matière non définie' }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $cours->classe->nom ?? 'N/A' }} 
                                    @if($cours->classe)
                                        - {{ $cours->classe->filiere->nom ?? '' }}
                                    @endif
                                    - {{ ucfirst($cours->type_cours) }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-door-closed"></i> Salle: {{ $cours->salle->nom ?? 'Non assignée' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($cours->statut == 'valide') bg-green-100 text-green-800
                                @elseif($cours->statut == 'en_cours') bg-blue-100 text-blue-800
                                @elseif($cours->statut == 'termine') bg-gray-100 text-gray-800
                                @elseif($cours->statut == 'annule') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $cours->statut_libelle ?? ucfirst($cours->statut) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('professeur.planning') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                <i class="fas fa-calendar-alt mr-2"></i>
                Voir mon planning complet
            </a>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center py-8">
            <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
            <p class="text-gray-600 text-lg">Aucun cours prévu aujourd'hui</p>
            <a href="{{ route('professeur.planning') }}" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-800">
                <i class="fas fa-calendar-alt mr-2"></i>
                Voir mon planning complet
            </a>
        </div>
    </div>
    @endif

    <!-- Grille inférieure -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Mes Filières -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-book text-purple-600"></i>
                    Mes Filières ({{ $data['filieres']->count() }})
                </h3>
            </div>
            <div class="p-6">
                @if($data['filieres']->count() > 0)
                    <div class="space-y-3">
                        @foreach($data['filieres'] as $filiere)
                            <a href="{{ route('professeur.stagiaires', ['filiere_id' => $filiere->id]) }}" 
                               class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-purple-50 hover:border-purple-300 border border-gray-200 transition-all cursor-pointer">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $filiere->nom }}</p>
                                    <p class="text-sm text-gray-500">{{ $filiere->niveau ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-purple-600">{{ $filiere->stagiaires_count ?? 0 }}</p>
                                    <p class="text-xs text-gray-500">stagiaires</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-book text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Aucune filière assignée</p>
                        <p class="text-sm text-gray-400 mt-2">Contactez l'administrateur pour assigner des filières</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Dernières Notes -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-clipboard-list text-green-600"></i>
                    Dernières Notes Saisies
                </h3>
            </div>
            <div class="p-6">
                @if($data['recent_notes']->count() > 0)
                    <div class="space-y-3">
                        @foreach($data['recent_notes'] as $note)
                            <a href="{{ route('professeur.stagiaires.notes', $note->stagiaire) }}" 
                               class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-green-50 hover:border-green-300 border border-gray-200 transition-all cursor-pointer">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">
                                        {{ $note->stagiaire->nom }} {{ $note->stagiaire->prenom }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $note->matiere->nom ?? 'Matière non définie' }} 
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded text-xs">
                                            {{ strtoupper($note->type_note) }}
                                        </span>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $note->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                <div class="text-right ml-4">
                                    <p class="text-2xl font-bold 
                                        @if($note->note >= 16) text-green-600
                                        @elseif($note->note >= 14) text-blue-600
                                        @elseif($note->note >= 12) text-yellow-600
                                        @elseif($note->note >= 10) text-orange-600
                                        @else text-red-600
                                        @endif">
                                        {{ number_format($note->note, 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500">/{{ $note->note_sur ?? 20 }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('professeur.notes-par-matiere') }}" 
                           class="text-green-600 hover:text-green-800 font-medium flex items-center justify-center">
                            <i class="fas fa-list mr-2"></i>
                            Voir toutes mes notes
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-clipboard-list text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Aucune note saisie récemment</p>
                        <a href="{{ route('professeur.stagiaires') }}" 
                           class="mt-3 inline-flex items-center text-green-600 hover:text-green-800">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Saisir une note
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Section Mes Matières -->
    @if($data['matieres']->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-book-open text-blue-600"></i>
                Mes Matières ({{ $data['matieres']->count() }})
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($data['matieres'] as $matiere)
                    <a href="{{ route('professeur.notes-par-matiere', ['matiere_id' => $matiere->id]) }}" 
                       class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 border border-blue-200 transition-all cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $matiere->nom }}</p>
                                <p class="text-sm text-gray-600">{{ $matiere->code }}</p>
                            </div>
                            <div class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold">
                                {{ $matiere->coefficient }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection