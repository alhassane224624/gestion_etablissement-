@extends('layouts.app')

@section('title', 'Modifier Stagiaire')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-user-edit text-blue-600"></i>
                Modifier Stagiaire: {{ $stagiaire->nom }} {{ $stagiaire->prenom }}
            </h1>
            <a href="{{ route('stagiaires.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <form action="{{ route('stagiaires.update', $stagiaire) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                    <input type="text" name="nom" value="{{ old('nom', $stagiaire->nom) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Prénom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="prenom" value="{{ old('prenom', $stagiaire->prenom) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prenom') border-red-500 @enderror">
                    @error('prenom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Matricule <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="matricule" value="{{ old('matricule', $stagiaire->matricule) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('matricule') border-red-500 @enderror">
                    @error('matricule')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date de Naissance
                    </label>
                    <input type="date" name="date_naissance" value="{{ old('date_naissance', $stagiaire->date_naissance?->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                    @error('date_naissance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lieu de Naissance
                    </label>
                    <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance', $stagiaire->lieu_naissance) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lieu_naissance') border-red-500 @enderror">
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
                        <option value="M" {{ old('sexe', $stagiaire->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sexe', $stagiaire->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                    @error('sexe')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Photo
                    </label>
                    @if($stagiaire->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $stagiaire->photo) }}" alt="{{ $stagiaire->nom }}" class="h-20 w-20 rounded-lg object-cover">
                        </div>
                    @endif
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
                    <input type="text" name="telephone" value="{{ old('telephone', $stagiaire->telephone) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror">
                    @error('telephone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $stagiaire->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse
                    </label>
                    <textarea name="adresse" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror">{{ old('adresse', $stagiaire->adresse) }}</textarea>
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
                    <input type="text" name="nom_tuteur" value="{{ old('nom_tuteur', $stagiaire->nom_tuteur) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom_tuteur') border-red-500 @enderror">
                    @error('nom_tuteur')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Téléphone du Tuteur
                    </label>
                    <input type="text" name="telephone_tuteur" value="{{ old('telephone_tuteur', $stagiaire->telephone_tuteur) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone_tuteur') border-red-500 @enderror">
                    @error('telephone_tuteur')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email du Tuteur
                    </label>
                    <input type="email" name="email_tuteur" value="{{ old('email_tuteur', $stagiaire->email_tuteur) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email_tuteur') border-red-500 @enderror">
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
                            <option value="{{ $filiere->id }}" {{ old('filiere_id', $stagiaire->filiere_id) == $filiere->id ? 'selected' : '' }}>
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
                            <option value="{{ $niveau->id }}" {{ old('niveau_id', $stagiaire->niveau_id) == $niveau->id ? 'selected' : '' }}>
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
                            <option value="{{ $classe->id }}" {{ old('classe_id', $stagiaire->classe_id) == $classe->id ? 'selected' : '' }}>
                                {{ $classe->nom }} - {{ $classe->niveau->nom ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('classe_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <select name="statut" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('statut') border-red-500 @enderror">
                        <option value="actif" {{ old('statut', $stagiaire->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="suspendu" {{ old('statut', $stagiaire->statut) == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        <option value="diplome" {{ old('statut', $stagiaire->statut) == 'diplome' ? 'selected' : '' }}>Diplômé</option>
                        <option value="abandonne" {{ old('statut', $stagiaire->statut) == 'abandonne' ? 'selected' : '' }}>Abandonné</option>
                        <option value="transfere" {{ old('statut', $stagiaire->statut) == 'transfere' ? 'selected' : '' }}>Transféré</option>
                    </select>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Motif de changement de statut
                    </label>
                    <textarea name="motif_statut" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('motif_statut') border-red-500 @enderror">{{ old('motif_statut', $stagiaire->motif_statut) }}</textarea>
                    @error('motif_statut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Frais d'inscription
                    </label>
                    <input type="number" name="frais_inscription" value="{{ old('frais_inscription', $stagiaire->frais_inscription) }}" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('frais_inscription') border-red-500 @enderror">
                    @error('frais_inscription')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="frais_payes" id="frais_payes" value="1" {{ old('frais_payes', $stagiaire->frais_payes) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="frais_payes" class="ml-2 block text-sm text-gray-700">
                        Frais payés
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $stagiaire->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Compte actif
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
                    Mettre à jour
                </button>
            </div>
        </div>
    </form>
</div>
@endsection