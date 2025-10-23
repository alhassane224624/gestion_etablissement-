<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Gestion des Stagiaires') }}</title>
        @auth
    @if (auth()->user()->isProfesseur())
        <a href="{{ route('professeur.stagiaires') }}" class="btn btn-info mt-4">Gérer les Stagiaires</a>
    @endif
@endauth
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Font Awesome pour les icônes -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles personnalisés -->
        <style>
            body {
                font-family: 'Figtree', sans-serif;
            }
            .bg-hero {
                background-image: linear-gradient(to bottom, rgba(0, 123, 255, 0.8), rgba(0, 123, 255, 0.2)), url('https://source.unsplash.com/1600x900/?education,students');
                background-size: cover;
                background-position: center;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Barre de navigation -->
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ route('dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Tableau de bord</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Connexion</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Inscription</a>
                        @endif
                    @endauth
                </div>
            @endif

            <!-- Section Hero -->
            <div class="bg-hero py-20 text-center text-white">
                <div class="max-w-7xl mx-auto px-6">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Bienvenue sur Gestion des établissements</h1>
                    <p class="text-lg md:text-xl mb-8">Gérez facilement les stagiaires et les filières de votre établissement avec une interface simple et intuitive.</p>
                    <a href="{{ route('stagiaires.inscription.form') }}" class="inline-block bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-all duration-300">
                        <i class="fas fa-user-plus mr-2"></i> Inscrire un Stagiaire
                    </a>
                </div>
            </div>

            <!-- Section Fonctionnalités -->
            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Gestion des Stagiaires -->
                    <a href="{{ route('stagiaires.index') }}" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-blue-500">
                        <div>
                            <div class="h-16 w-16 bg-blue-50 dark:bg-blue-800/20 flex items-center justify-center rounded-full">
                                <i class="fas fa-users text-2xl text-blue-500"></i>
                            </div>
                            <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Gestion des Stagiaires</h2>
                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Consultez, ajoutez, modifiez ou supprimez des stagiaires. Exportez la liste en Excel pour un suivi simplifié.
                            </p>
                        </div>
                        <i class="fas fa-arrow-right self-center shrink-0 text-blue-500 w-6 h-6 mx-6"></i>
                    </a>

                    <!-- Gestion des Filières -->
                    <a href="{{ route('filieres.index') }}" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-blue-500">
                        <div>
                            <div class="h-16 w-16 bg-blue-50 dark:bg-blue-800/20 flex items-center justify-center rounded-full">
                                <i class="fas fa-graduation-cap text-2xl text-blue-500"></i>
                            </div>
                            <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Gestion des Filières</h2>
                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Organisez les filières de votre établissement. Ajoutez, modifiez ou supprimez des filières en toute simplicité.
                            </p>
                        </div>
                        <i class="fas fa-arrow-right self-center shrink-0 text-blue-500 w-6 h-6 mx-6"></i>
                    </a>

                    <!-- Inscription des Stagiaires -->
                    <a href="{{ route('stagiaires.inscription.form') }}" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-blue-500">
                        <div>
                            <div class="h-16 w-16 bg-blue-50 dark:bg-blue-800/20 flex items-center justify-center rounded-full">
                                <i class="fas fa-user-plus text-2xl text-blue-500"></i>
                            </div>
                            <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Inscription des Stagiaires</h2>
                            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                Inscrivez de nouveaux stagiaires rapidement et associez-les à une filière existante.
                            </p>
                        </div>
                        <i class="fas fa-arrow-right self-center shrink-0 text-blue-500 w-6 h-6 mx-6"></i>
                    </a>
                </div>
            </div>

            <!-- Pied de page -->
            <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                <div class="text-center text-sm sm:text-left">
                    &copy; {{ date('Y') }} Gestion des Stagiaires. Tous droits réservés.
                </div>
                <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                    ALHASSANE DIANE
                </div>
            </div>
        </div>
    </body>
</html>