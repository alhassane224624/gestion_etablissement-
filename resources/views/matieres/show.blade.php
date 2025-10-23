@extends('layouts.app')

@section('title', 'Détails de la Matière')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-10">

    <!-- Fil d’Ariane -->
    <nav class="text-sm text-gray-600 mb-4">
        <a href="{{ route('matieres.index') }}" class="text-blue-600 hover:underline">Matières</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="font-semibold text-gray-800">{{ $matiere->nom }}</span>
    </nav>

    <!-- En-tête -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-4">
                <div class="h-16 w-16 rounded-xl flex items-center justify-center border-4"
                    style="background-color: {{ $matiere->couleur ?? '#3B82F6' }}; border-color: {{ $matiere->couleur ?? '#3B82F6' }};">
                    <span class="text-2xl font-bold text-white">{{ $matiere->code }}</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $matiere->nom }}</h1>
                    <p class="text-gray-600 text-sm mt-1">Code: {{ $matiere->code }} | Coefficient: {{ $matiere->coefficient }}</p>
                </div>
            </div>

            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('matieres.edit', $matiere) }}"
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                <a href="{{ route('matieres.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques (nouvelle section modernisée) -->
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-pie text-blue-600 mr-2"></i> Statistiques Globales
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Notes</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_notes'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Moyenne -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Moyenne</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">
                                {{ $stats['moyenne_generale'] ? number_format($stats['moyenne_generale'], 2) : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    @if($stats['moyenne_generale'])
                        <span class="text-sm px-2 py-1 rounded-full {{ $stats['moyenne_generale'] >= 10 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $stats['moyenne_generale'] >= 10 ? 'Satisfaisante' : 'Faible' }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Filières -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-book text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Filières</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['filieres_count'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Niveaux -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center">
                            <i class="fas fa-layer-group text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Niveaux</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['niveaux_count'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations générales -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-md border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informations Générales
                </h2>
            </div>
            <div class="p-6 space-y-3 text-gray-800">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="font-medium text-gray-600">Nom:</span>
                    <span class="font-semibold">{{ $matiere->nom }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="font-medium text-gray-600">Code:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-800">{{ $matiere->code }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="font-medium text-gray-600">Coefficient:</span>
                    <span class="px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">{{ $matiere->coefficient }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="font-medium text-gray-600">Couleur:</span>
                    <div class="flex items-center space-x-2">
                        <div class="h-6 w-6 rounded border border-gray-300"
                             style="background-color: {{ $matiere->couleur ?? '#3B82F6' }}"></div>
                        <span>{{ $matiere->couleur ?? 'N/A' }}</span>
                    </div>
                </div>

                @if($matiere->description)
                <div class="pt-2">
                    <span class="font-medium text-gray-600 block mb-2">Description:</span>
                    <p class="bg-gray-50 text-sm p-3 rounded-lg leading-relaxed">{{ $matiere->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Statistiques des notes -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                    Statistiques des Notes
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="font-medium text-gray-600">Total des notes:</span>
                    <span class="font-semibold text-gray-900">{{ $stats['total_notes'] }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="font-medium text-gray-600">Moyenne générale:</span>
                    <span class="text-2xl font-bold {{ $stats['moyenne_generale'] >= 10 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['moyenne_generale'] ? number_format($stats['moyenne_generale'], 2) : 'N/A' }}
                    </span>
                </div>

                @if($stats['total_notes'] > 0)
                    <div class="pt-3">
                        @php
                            $excellent = $matiere->notes->where('note', '>=', 16)->count();
                            $bien = $matiere->notes->whereBetween('note', [14, 15.99])->count();
                            $assezBien = $matiere->notes->whereBetween('note', [12, 13.99])->count();
                            $passable = $matiere->notes->whereBetween('note', [10, 11.99])->count();
                            $insuffisant = $matiere->notes->where('note', '<', 10)->count();
                        @endphp
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-3 font-medium">Répartition des notes :</p>
                            <canvas id="notesChart"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($stats['total_notes'] > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('notesChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Excellent (≥16)', 'Bien (14–16)', 'Assez bien (12–14)', 'Passable (10–12)', 'Insuffisant (<10)'],
            datasets: [{
                label: 'Répartition des notes',
                data: [{{ $excellent }}, {{ $bien }}, {{ $assezBien }}, {{ $passable }}, {{ $insuffisant }}],
                backgroundColor: ['#16a34a', '#3b82f6', '#facc15', '#f97316', '#ef4444'],
                borderRadius: 6,
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endpush
@endif
@endsection
    