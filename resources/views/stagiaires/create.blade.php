@extends('layouts.app')

@section('title', 'Ajouter un Stagiaire')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-user-plus text-blue-600"></i>
                Ajouter un Nouveau Stagiaire
            </h1>
            <a href="{{ route('stagiaires.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <form action="{{ route('stagiaires.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Informations personnelles -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                <i class="fas fa-user text-blue-600"></i>
                Informations Personnelles
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Prénom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prenom') border-red-500 @enderror">
                    @error('prenom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Matricule <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="matricule" value="{{ old('matricule') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('matricule') border-red-500 @enderror">
                    @error('matricule')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date de Naissance
                    </label>
                    <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                    @error('date_naissance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lieu de Naissance
                    </label>
                    <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lieu_naissance') border-red-500 @enderror">
                    @error('lieu_naissance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sexe
                    </label>
                    <select name="sexe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sexe') border-red-500 @enderror">
                        <option value="">Sélectionner</option>
                        <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                    @error('sexe')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Photo
                    </label>
                    <input type="file" name="photo" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('photo') border-red-500 @enderror">
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                <i class="fas fa-address-book text-green-600"></i>
                Informations de Contact
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone
                    </label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                    @error('telephone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse
                    </label>
                    <textarea name="adresse" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror">{{ old('adresse') }}</textarea>
                    @error('adresse')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tuteur -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                <i class="fas fa-users text-purple-600"></i>
                Informations du Tuteur
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du Tuteur
                    </label>
                    <input type="text" name="nom_tuteur" value="{{ old('nom_tuteur') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom_tuteur') border-red-500 @enderror">
                    @error('nom_tuteur')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone du Tuteur
                    </label>
                    <input type="text" name="telephone_tuteur" value="{{ old('telephone_tuteur') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone_tuteur') border-red-500 @enderror">
                    @error('telephone_tuteur')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email du Tuteur
                    </label>
                    <input type="email" name="email_tuteur" value="{{ old('email_tuteur') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email_tuteur') border-red-500 @enderror">
                    @error('email_tuteur')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informations scolaires -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                <i class="fas fa-graduation-cap text-orange-600"></i>
                Informations Scolaires
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Filière <span class="text-red-500">*</span>
                    </label>
                    <select name="filiere_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('filiere_id') border-red-500 @enderror">
                        <option value="">Sélectionner une filière</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                {{ $filiere->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('filiere_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Niveau
                    </label>
                    <select name="niveau_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('niveau_id') border-red-500 @enderror">
                        <option value="">Sélectionner un niveau</option>
                        @foreach($niveaux as $niveau)
                            <option value="{{ $niveau->id }}" {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>
                                {{ $niveau->nom }} ({{ $niveau->filiere->nom }})
                            </option>
                        @endforeach
                    </select>
                    @error('niveau_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Classe
                    </label>
                    <select name="classe_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('classe_id') border-red-500 @enderror">
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }} - {{ $classe->niveau->nom ?? '' }} ({{ $classe->effectif_actuel }}/{{ $classe->effectif_max }})
                            </option>
                        @endforeach
                    </select>
                    @error('classe_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date d'inscription
                    </label>
                    <input type="date" name="date_inscription" value="{{ old('date_inscription', date('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_inscription') border-red-500 @enderror">
                    @error('date_inscription')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Frais d'inscription
                    </label>
                    <input type="number" name="frais_inscription" value="{{ old('frais_inscription') }}" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('frais_inscription') border-red-500 @enderror">
                    @error('frais_inscription')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="frais_payes" id="frais_payes" value="1" {{ old('frais_payes') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="frais_payes" class="ml-2 block text-sm text-gray-700">
                        Frais payés
                    </label>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('stagiaires.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer
                </button>
            </div>
        </div>
    </form>
</div>
@endsection