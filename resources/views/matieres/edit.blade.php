@extends('layouts.app')

@section('title', 'Modifier Matière')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-edit text-blue-600"></i>
                Modifier Matière: {{ $matiere->nom }}
            </h1>
            <a href="{{ route('matieres.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>

    <!-- Messages d'erreur globaux -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg shadow-sm mb-4">
            <strong>⚠️ Erreurs détectées :</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ route('matieres.update', $matiere) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Informations de base -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                <i class="fas fa-info-circle text-blue-600"></i>
                Informations de Base
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom <span class="text-red-500">*</span></label>
                    <input type="text" name="nom" value="{{ old('nom', $matiere->nom) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $matiere->code) }}" required maxlength="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Coefficient <span class="text-red-500">*</span></label>
                    <input type="number" name="coefficient" value="{{ old('coefficient', $matiere->coefficient) }}" required min="1" max="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Couleur</label>
                    <input type="color" name="couleur" value="{{ old('couleur', $matiere->couleur ?? '#3B82F6') }}" class="w-full h-10 px-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $matiere->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Filières -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                <i class="fas fa-book text-purple-600"></i>
                Filières Associées
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($filieres as $filiere)
                    <div class="flex items-center">
                        <input type="checkbox" name="filieres[]" id="filiere_{{ $filiere->id }}" value="{{ $filiere->id }}" 
                            {{ in_array($filiere->id, old('filieres', $matiere->filieres->pluck('id')->toArray())) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="filiere_{{ $filiere->id }}" class="ml-2 block text-sm text-gray-700">{{ $filiere->nom }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Niveaux -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                <i class="fas fa-layer-group text-green-600"></i>
                Niveaux et Configuration
            </h2>

            <div id="niveaux-container" class="space-y-4">
                @foreach($niveaux as $index => $niveau)
                    @php
                        $niveauData = $matiere->niveaux->where('id', $niveau->id)->first();
                        $isChecked = $niveauData !== null;
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <!-- ✅ Correction ici -->
                            <input type="hidden" name="niveaux[{{ $index }}][niveau_id]" value="">
                            <input type="checkbox" name="niveaux[{{ $index }}][niveau_id]" id="niveau_{{ $niveau->id }}" 
                                value="{{ $niveau->id }}" {{ $isChecked ? 'checked' : '' }} 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded niveau-checkbox">
                            <label for="niveau_{{ $niveau->id }}" class="ml-2 block text-sm font-medium text-gray-900">
                                {{ $niveau->nom }} ({{ $niveau->filiere->nom }})
                            </label>
                        </div>

                        <div class="ml-6 grid grid-cols-1 md:grid-cols-2 gap-4 niveau-details" 
                             style="display: {{ $isChecked ? 'grid' : 'none' }};">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Heures de cours</label>
                                <input type="number" name="niveaux[{{ $index }}][heures_cours]" 
                                    value="{{ $niveauData->pivot->heures_cours ?? '' }}" min="0" 
                                    placeholder="Ex: 30" 
                                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="niveaux[{{ $index }}][is_obligatoire]" 
                                    id="obligatoire_{{ $niveau->id }}" value="1" 
                                    {{ ($niveauData->pivot->is_obligatoire ?? true) ? 'checked' : '' }} 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="obligatoire_{{ $niveau->id }}" class="ml-2 block text-sm text-gray-700">
                                    Matière obligatoire
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Boutons -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('matieres.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> Mettre à jour
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.niveau-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const details = this.closest('.border').querySelector('.niveau-details');
            details.style.display = this.checked ? 'grid' : 'none';
        });
    });
});
</script>
@endpush
@endsection
