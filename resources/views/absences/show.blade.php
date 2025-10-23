@extends('layouts.app')

@section('title', 'Détails de l\'Absence')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-user-times text-red-600"></i>
                    Détails de l'Absence
                </h1>
                <p class="text-gray-600 mt-1">{{ $absence->date->format('d/m/Y') }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('absences.edit', $absence) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('absences.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informations du stagiaire -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-user text-blue-600"></i>
                    Informations du Stagiaire
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center space-x-4">
                    @if($absence->stagiaire->photo)
                        <img src="{{ asset('storage/' . $absence->stagiaire->photo) }}" alt="{{ $absence->stagiaire->nom }}" class="h-16 w-16 rounded-full object-cover">
                    @else
                        <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-900 text-lg">{{ $absence->stagiaire->nom }} {{ $absence->stagiaire->prenom }}</p>
                        <p class="text-sm text-gray-600">{{ $absence->stagiaire->matricule }}</p>
                    </div>
                </div>
                
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Filière:</span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                        {{ $absence->stagiaire->filiere->nom ?? 'N/A' }}
                    </span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Classe:</span>
                    <span class="text-gray-900">{{ $absence->stagiaire->classe->nom ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Téléphone:</span>
                    <span class="text-gray-900">{{ $absence->stagiaire->telephone ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600 font-medium">Email:</span>
                    <span class="text-gray-900">{{ $absence->stagiaire->email ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Détails de l'absence -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-info-circle text-green-600"></i>
                    Détails de l'Absence
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Date:</span>
                    <span class="text-gray-900 font-semibold">{{ $absence->date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Jour:</span>
                    <span class="text-gray-900">{{ $absence->date->translatedFormat('l') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Type:</span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', $absence->type)) }}
                    </span>
                </div>
                @if($absence->type == 'heure')
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Période:</span>
                    <span class="text-gray-900">{{ $absence->heure_debut }} - {{ $absence->heure_fin }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Statut:</span>
                    @if($absence->justifiee)
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check"></i> Justifiée
                        </span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            <i class="fas fa-times"></i> Non justifiée
                        </span>
                    @endif
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-600 font-medium">Enregistrée par:</span>
                    <span class="text-gray-900">{{ $absence->creator->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Motif et document -->
    @if($absence->motif || $absence->document_justificatif)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-file-alt text-purple-600"></i>
                Justification
            </h2>
        </div>
        <div class="p-6 space-y-4">
            @if($absence->motif)
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">Motif:</h3>
                <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $absence->motif }}</p>
            </div>
            @endif

            @if($absence->document_justificatif)
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-2">Document justificatif:</h3>
                <div class="flex items-center space-x-3">
                    <i class="fas fa-file-pdf text-red-600 text-2xl"></i>
                    <a href="{{ asset('storage/' . $absence->document_justificatif) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                        Télécharger le document
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Informations système -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-cog text-gray-600"></i>
                Informations Système
            </h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium">Date de création:</span>
                <span class="text-gray-900">{{ $absence->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium">Dernière modification:</span>
                <span class="text-gray-900">{{ $absence->updated_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600 font-medium">Créé par:</span>
                <span class="text-gray-900">{{ $absence->creator->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection