<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-indigo-100 to-white py-12 px-6">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
            
            <!-- Titre et texte d'accueil -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-extrabold text-indigo-700 mb-2">Bienvenue à l’École Supérieure EMSI</h1>
                <p class="text-gray-600 text-sm">
                    Connectez-vous à votre espace pour accéder à vos cours, résultats et activités pédagogiques.
                    Ensemble, construisons votre avenir avec excellence et innovation.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Adresse e-mail')" />
                    <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" 
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Mot de passe')" />
                    <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    @endif
                </div>

                <!-- Bouton de connexion -->
                <div class="mt-6">
                    <x-primary-button class="w-full justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-md shadow-md transition duration-200">
                        {{ __('Se connecter') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Lien vers l'inscription -->
            @if (Route::has('register'))
                <p class="mt-6 text-center text-sm text-gray-600">
                    Vous n’avez pas encore de compte ?
                    <a href="{{ route('register') }}" class="text-indigo-600 font-medium hover:underline">
                        Inscrivez-vous ici
                    </a>
                </p>
            @endif
        </div>
    </div>
</x-guest-layout>
