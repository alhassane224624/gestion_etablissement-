@extends('layouts.app')

@section('title', 'Détails Stagiaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="container mx-auto px-4 max-w-7xl space-y-6">

        <!-- Header avec effet glassmorphism -->
        <div class="relative overflow-hidden bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-6">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 via-purple-600/5 to-pink-600/5"></div>
            <div class="relative flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-xl">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900">Profil Stagiaire</h1>
                        <p class="text-sm text-gray-600">Informations détaillées et statistiques</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('stagiaires.edit', $stagiaire) }}" 
                       class="group inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-edit mr-2 group-hover:rotate-12 transition-transform"></i>
                        Modifier
                    </a>
                    <a href="{{ route('stagiaires.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-gray-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne gauche -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Carte Profil avec Avatar -->
                <div class="relative overflow-hidden bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 via-purple-600/10 to-pink-600/10"></div>
                    <div class="relative p-6">
                        <div class="text-center">
                            <!-- Avatar avec effet 3D -->
                            <div class="relative inline-block mb-4">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full blur-2xl opacity-30 animate-pulse"></div>
                                <img src="{{ $stagiaire->photo_url }}" 
                                     alt="{{ $stagiaire->nom_complet }}" 
                                     class="relative w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-2xl transform hover:scale-110 transition-all duration-300">
                            </div>
                            
                            <h2 class="text-2xl font-black text-gray-900 mb-1">{{ $stagiaire->nom_complet }}</h2>
                            <p class="text-sm font-mono text-gray-600 mb-3 bg-gray-100 inline-block px-3 py-1 rounded-lg">{{ $stagiaire->matricule }}</p>
                            
                            @php
                                $statutConfig = [
                                    'actif' => ['bg' => 'from-emerald-500 to-green-600', 'icon' => 'fa-check-circle'],
                                    'suspendu' => ['bg' => 'from-yellow-500 to-amber-600', 'icon' => 'fa-pause-circle'],
                                    'diplome' => ['bg' => 'from-blue-500 to-indigo-600', 'icon' => 'fa-graduation-cap'],
                                    'abandonne' => ['bg' => 'from-red-500 to-rose-600', 'icon' => 'fa-times-circle'],
                                    'transfere' => ['bg' => 'from-purple-500 to-violet-600', 'icon' => 'fa-exchange-alt'],
                                ];
                                $config = $statutConfig[$stagiaire->statut] ?? ['bg' => 'from-gray-500 to-slate-600', 'icon' => 'fa-circle'];
                            @endphp
                            
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r {{ $config['bg'] }} text-white rounded-full font-semibold text-sm shadow-lg">
                                <i class="fas {{ $config['icon'] }}"></i>
                                {{ $stagiaire->statut_libelle }}
                            </div>

                            @if($stagiaire->motif_statut)
                            <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-500 rounded-lg text-left">
                                <p class="text-sm text-gray-700"><i class="fas fa-info-circle text-blue-600 mr-2"></i>{{ $stagiaire->motif_statut }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistiques Visuelles -->
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-chart-pie"></i>
                            Statistiques
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @php
                            $stats_items = [
                                ['label' => 'Total Notes', 'value' => $stats['total_notes'], 'icon' => 'fa-clipboard-check', 'color' => 'blue'],
                                ['label' => 'Moyenne', 'value' => number_format($stats['moyenne_generale'], 2) . '/20', 'icon' => 'fa-chart-line', 'color' => $stats['moyenne_generale'] >= 10 ? 'green' : 'red'],
                                ['label' => 'Absences', 'value' => $stats['total_absences'], 'icon' => 'fa-calendar-times', 'color' => 'orange'],
                                ['label' => 'Injustifiées', 'value' => $stats['absences_injustifiees'], 'icon' => 'fa-exclamation-triangle', 'color' => 'red'],
                            ];
                        @endphp
                        
                        @foreach($stats_items as $item)
                        <div class="group flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-{{ $item['color'] }}-50 to-{{ $item['color'] }}-100 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-xl bg-{{ $item['color'] }}-500 text-white flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-6 transition-all shadow-lg">
                                    <i class="fas {{ $item['icon'] }} text-lg"></i>
                                </div>
                                <span class="font-semibold text-gray-700">{{ $item['label'] }}</span>
                            </div>
                            <span class="text-2xl font-black text-{{ $item['color'] }}-600">{{ $item['value'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Changement de Statut -->
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-600 to-red-600 p-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-exchange-alt"></i>
                            Changer le Statut
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('stagiaires.change-statut', $stagiaire) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nouveau Statut</label>
                                <select name="statut" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="actif" {{ $stagiaire->statut == 'actif' ? 'selected' : '' }}>✅ Actif</option>
                                    <option value="suspendu" {{ $stagiaire->statut == 'suspendu' ? 'selected' : '' }}>⏸️ Suspendu</option>
                                    <option value="diplome" {{ $stagiaire->statut == 'diplome' ? 'selected' : '' }}>🎓 Diplômé</option>
                                    <option value="abandonne" {{ $stagiaire->statut == 'abandonne' ? 'selected' : '' }}>❌ Abandonné</option>
                                    <option value="transfere" {{ $stagiaire->statut == 'transfere' ? 'selected' : '' }}>🔄 Transféré</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Motif</label>
                                <textarea name="motif_statut" rows="3" placeholder="Raison du changement..." 
                                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"></textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i>
                                Enregistrer
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Colonne droite -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Informations Personnelles -->
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-user"></i>
                            Informations Personnelles
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $personal_info = [
                                    ['label' => 'Date de Naissance', 'value' => $stagiaire->date_naissance ? $stagiaire->date_naissance->format('d/m/Y') . ($stagiaire->age ? " ({$stagiaire->age} ans)" : '') : 'N/A', 'icon' => 'fa-birthday-cake'],
                                    ['label' => 'Lieu de Naissance', 'value' => $stagiaire->lieu_naissance ?? 'N/A', 'icon' => 'fa-map-marker-alt'],
                                    ['label' => 'Sexe', 'value' => $stagiaire->sexe == 'M' ? '👨 Masculin' : ($stagiaire->sexe == 'F' ? '👩 Féminin' : 'N/A'), 'icon' => 'fa-venus-mars'],
                                    ['label' => 'Téléphone', 'value' => $stagiaire->telephone ?? 'N/A', 'icon' => 'fa-phone'],
                                    ['label' => 'Email', 'value' => $stagiaire->email ?? 'N/A', 'icon' => 'fa-envelope'],
                                    ['label' => 'Adresse', 'value' => $stagiaire->adresse ?? 'N/A', 'icon' => 'fa-home'],
                                ];
                            @endphp
                            
                            @foreach($personal_info as $info)
                            <div class="group p-4 rounded-xl bg-gradient-to-br from-gray-50 to-blue-50 border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-300">
                                <div class="flex items-center gap-3 mb-2">
                                    <i class="fas {{ $info['icon'] }} text-blue-600"></i>
                                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wide">{{ $info['label'] }}</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 pl-7">{{ $info['value'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Informations Tuteur -->
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-users"></i>
                            Informations du Tuteur
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @php
                                $tuteur_info = [
                                    ['label' => 'Nom', 'value' => $stagiaire->nom_tuteur ?? 'N/A', 'icon' => 'fa-user-tie'],
                                    ['label' => 'Téléphone', 'value' => $stagiaire->telephone_tuteur ?? 'N/A', 'icon' => 'fa-phone'],
                                    ['label' => 'Email', 'value' => $stagiaire->email_tuteur ?? 'N/A', 'icon' => 'fa-envelope'],
                                ];
                            @endphp
                            
                            @foreach($tuteur_info as $info)
                            <div class="p-4 rounded-xl bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas {{ $info['icon'] }} text-purple-600"></i>
                                    <p class="text-xs font-bold text-gray-600 uppercase">{{ $info['label'] }}</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">{{ $info['value'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Informations Scolaires -->
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-600 to-amber-600 p-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-graduation-cap"></i>
                            Informations Scolaires
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $school_info = [
                                    ['label' => 'Filière', 'value' => $stagiaire->filiere->nom ?? 'N/A', 'icon' => 'fa-book', 'color' => 'blue'],
                                    ['label' => 'Niveau', 'value' => $stagiaire->niveau->nom ?? 'N/A', 'icon' => 'fa-layer-group', 'color' => 'indigo'],
                                    ['label' => 'Classe', 'value' => $stagiaire->classe->nom ?? 'N/A', 'icon' => 'fa-door-open', 'color' => 'purple'],
                                    ['label' => 'Date Inscription', 'value' => $stagiaire->date_inscription ? $stagiaire->date_inscription->format('d/m/Y') : 'N/A', 'icon' => 'fa-calendar-check', 'color' => 'green'],
                                    ['label' => 'Frais Inscription', 'value' => $stagiaire->frais_inscription ? number_format($stagiaire->frais_inscription, 2) . ' DH' : 'N/A', 'icon' => 'fa-money-bill-wave', 'color' => 'emerald'],
                                    ['label' => 'Paiement', 'value' => $stagiaire->frais_payes ? '✅ Payé' : '❌ Non payé', 'icon' => 'fa-credit-card', 'color' => $stagiaire->frais_payes ? 'green' : 'red'],
                                ];
                            @endphp
                            
                            @foreach($school_info as $info)
                            <div class="group p-4 rounded-xl bg-gradient-to-br from-{{ $info['color'] }}-50 to-white border border-{{ $info['color'] }}-200 hover:shadow-lg transition-all">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="h-8 w-8 rounded-lg bg-{{ $info['color'] }}-500 text-white flex items-center justify-center text-sm transform group-hover:scale-110 transition-transform">
                                        <i class="fas {{ $info['icon'] }}"></i>
                                    </div>
                                    <p class="text-xs font-bold text-gray-600 uppercase">{{ $info['label'] }}</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 pl-11">{{ $info['value'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Notes Récentes -->
                @if($stagiaire->notes->count() > 0)
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-clipboard-list"></i>
                            Notes Récentes
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-hidden rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Matière</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Note</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($stagiaire->notes->take(5) as $note)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $note->matiere->nom ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $note->note >= 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ number_format($note->note, 2) }}/20
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $note->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Absences Récentes -->
                @if($stagiaire->absences->count() > 0)
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-rose-600 p-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-calendar-times"></i>
                            Absences Récentes
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-hidden rounded-xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Matière</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Statut</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Motif</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach($stagiaire->absences->take(5) as $absence)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $absence->date->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $absence->matiere->nom ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">
                                            @if($absence->justifiee)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Justifiée
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Non justifiée
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $absence->motif ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Informations Système -->
                <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-slate-800 p-5">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-cog"></i>
                            Informations Système
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $system_info = [
                                    ['label' => 'Créé le', 'value' => $stagiaire->created_at->format('d/m/Y H:i'), 'icon' => 'fa-clock'],
                                    ['label' => 'Créé par', 'value' => $stagiaire->createdBy->name ?? 'N/A', 'icon' => 'fa-user-shield'],
                                    ['label' => 'Modifié le', 'value' => $stagiaire->updated_at->format('d/m/Y H:i'), 'icon' => 'fa-edit'],
                                    ['label' => 'Compte actif', 'value' => $stagiaire->is_active ? '✅ Oui' : '❌ Non', 'icon' => 'fa-toggle-on'],
                                ];
                            @endphp
                            
                            @foreach($system_info as $info)
                            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas {{ $info['icon'] }} text-gray-600"></i>
                                    <p class="text-xs font-bold text-gray-600 uppercase">{{ $info['label'] }}</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-900">{{ $info['value'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection