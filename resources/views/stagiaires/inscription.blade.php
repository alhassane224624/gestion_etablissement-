@extends('layouts.app')

@section('title', 'Inscription Stagiaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <!-- En-tête -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                <i class="fas fa-user-plus text-blue-600"></i>
                Inscription Stagiaire
            </h1>
            <p class="text-gray-600">Remplissez le formulaire pour vous inscrire</p>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Erreurs:</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire -->
        <form method="POST" action="{{ route('stagiaires.inscription.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg p-8 space-y-6">
            @csrf

            <!-- Informations de base -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user text-blue-600"></i>
                    Informations Personnelles
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nom') border-red-500 @enderror">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prenom') border-red-500 @enderror">
                        @error('prenom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Matricule <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="matricule" value="{{ old('matricule') }}" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('matricule') border-red-500 @enderror"
                            placeholder="Ex: ST2024001">
                        @error('matricule')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Date de Naissance
                        </label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                        @error('date_naissance')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sexe
                        </label>
                        <select name="sexe" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('sexe') border-red-500 @enderror">
                            <option value="">Sélectionner</option>
                            <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('sexe')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Photo (optionnel)
                        </label>
                        <input type="file" name="photo" accept="image/*" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('photo') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Format: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-phone text-green-600"></i>
                    Coordonnées
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone
                        </label>
                        <input type="tel" name="telephone" value="{{ old('telephone') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telephone') border-red-500 @enderror"
                            placeholder="Ex: 0612345678">
                        @error('telephone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            placeholder="exemple@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse
                        </label>
                        <textarea name="adresse" rows="3" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('adresse') border-red-500 @enderror"
                            placeholder="Adresse complète">{{ old('adresse') }}</textarea>
                        @error('adresse')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Filière -->
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-graduation-cap text-orange-600"></i>
                    Filière
                </h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Choisissez votre filière <span class="text-red-500">*</span>
                    </label>
                    <select name="filiere_id" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('filiere_id') border-red-500 @enderror">
                        <option value="">Sélectionnez une filière</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                {{ $filiere->nom }} - {{ $filiere->code ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('filiere_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à l'accueil
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition shadow-lg hover:shadow-xl">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Soumettre ma demande
                </button>
            </div>
        </form>

        <!-- Informations supplémentaires -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6 text-sm text-gray-700">
            <h3 class="font-semibold mb-2">
                <i class="fas fa-info-circle text-blue-600"></i>
                Informations importantes
            </h3>
            <ul class="space-y-1 ml-4 list-disc">
                <li>Tous les champs marqués d'une étoile (*) sont obligatoires</li>
                <li>Votre matricule doit être unique</li>
                <li>Vous recevrez une confirmation par email après traitement de votre demande</li>
                <li>Pour toute question, contactez l'administration</li>
            </ul>
        </div>
    </div>
</div>
@endsection