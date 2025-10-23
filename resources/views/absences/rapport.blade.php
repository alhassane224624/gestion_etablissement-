@extends('layouts.app')

@section('title', 'Rapport des Absences')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-chart-bar text-purple-600"></i>
                    Rapport des Absences
                </h1>
                <p class="text-gray-600 mt-1">
                    Du {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('absences.export', ['filiere_id' => $filiere_id, 'date_debut' => $date_debut, 'date_fin' => $date_fin]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                    <i class="fas fa-file-excel mr-2"></i>
                    Exporter
                </a>
                <a href="{{ route('absences.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('absences.rapport') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filière</label>
                <select name="filiere_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Toutes les filières</option>
                    @foreach($filieres as $filiere)
                        <option value="{{ $filiere->id }}" {{ $filiere_id == $filiere->id ? 'selected' : '' }}>
                            {{ $filiere->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" name="date_debut" value="{{ $date_debut }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                <input type="date" name="date_fin" value="{{ $date_fin }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Statistiques par stagiaire -->
    @if($stats_stagiaires->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-users text-blue-600"></i>
                Statistiques par Stagiaire ({{ $stats_stagiaires->count() }} stagiaire(s))
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stagiaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filière</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Absences</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Justifiées</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Non Justifiées</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jours Absents</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stats_stagiaires->sortByDesc('total_absences') as $stat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $stat['stagiaire']->nom }} {{ $stat['stagiaire']->prenom }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $stat['stagiaire']->matricule }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $stat['stagiaire']->filiere->nom ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-lg font-bold text-gray-900">{{ $stat['total_absences'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $stat['absences_justifiees'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                    @if($stat['absences_injustifiees'] > 5) bg-red-100 text-red-800
                                    @elseif($stat['absences_injustifiees'] > 2) bg-orange-100 text-orange-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $stat['absences_injustifiees'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium text-gray-900">{{ number_format($stat['jours_absents'], 1) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('stagiaires.show', $stat['stagiaire']) }}" class="text-blue-600 hover:text-blue-900" title="Voir le profil">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Résumé global -->
        <div class="p-6 bg-gray-50 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600">Total Absences</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats_stagiaires->sum('total_absences') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Justifiées</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats_stagiaires->sum('absences_justifiees') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Non Justifiées</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats_stagiaires->sum('absences_injustifiees') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Total Jours</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats_stagiaires->sum('jours_absents'), 1) }}</p>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
        <p class="text-gray-600 text-lg">Aucune absence trouvée pour cette période</p>
    </div>
    @endif
</div>
@endsection