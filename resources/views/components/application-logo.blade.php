<!-- Logo EMSI moderne et élégant -->
<div class="inline-block">
    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'w-20 h-20']) }}>
        <defs>
            <!-- Dégradés modernes -->
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#6366f1" />
                <stop offset="50%" stop-color="#8b5cf6" />
                <stop offset="100%" stop-color="#a855f7" />
            </linearGradient>
            
            <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#f59e0b" />
                <stop offset="100%" stop-color="#f97316" />
            </linearGradient>

            <!-- Ombre portée douce -->
            <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur in="SourceAlpha" stdDeviation="3"/>
                <feOffset dx="0" dy="2" result="offsetblur"/>
                <feComponentTransfer>
                    <feFuncA type="linear" slope="0.3"/>
                </feComponentTransfer>
                <feMerge>
                    <feMergeNode/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>

            <!-- Animation de pulsation -->
            <radialGradient id="pulse">
                <stop offset="0%" stop-color="#6366f1" stop-opacity="0.3">
                    <animate attributeName="stop-opacity" values="0.3;0.6;0.3" dur="2s" repeatCount="indefinite"/>
                </stop>
                <stop offset="100%" stop-color="#8b5cf6" stop-opacity="0">
                    <animate attributeName="stop-opacity" values="0;0.2;0" dur="2s" repeatCount="indefinite"/>
                </stop>
            </radialGradient>
        </defs>

        <!-- Cercle de fond avec animation -->
        <circle cx="100" cy="100" r="85" fill="url(#pulse)">
            <animate attributeName="r" values="85;90;85" dur="2s" repeatCount="indefinite"/>
        </circle>

        <!-- Cercle décoratif principal -->
        <circle cx="100" cy="100" r="75" fill="none" stroke="url(#grad1)" stroke-width="2" opacity="0.2"/>
        
        <!-- Forme géométrique moderne (hexagone) -->
        <path d="M 100,35 L 145,62.5 L 145,117.5 L 100,145 L 55,117.5 L 55,62.5 Z" 
              fill="url(#grad1)" 
              opacity="0.12" 
              filter="url(#shadow)"/>

        <!-- Icône livre stylisée -->
        <g transform="translate(100, 85)">
            <!-- Pages du livre -->
            <path d="M -20,-15 L -20,20 L -5,25 L -5,-10 Z" fill="url(#grad1)" opacity="0.9"/>
            <path d="M 5,-10 L 5,25 L 20,20 L 20,-15 Z" fill="url(#grad1)" opacity="0.7"/>
            
            <!-- Ligne centrale -->
            <line x1="0" y1="-10" x2="0" y2="25" stroke="url(#grad1)" stroke-width="2"/>
            
            <!-- Détails des pages -->
            <line x1="-15" y1="-5" x2="-8" y2="-5" stroke="#ffffff" stroke-width="1" opacity="0.5"/>
            <line x1="-15" y1="0" x2="-8" y2="0" stroke="#ffffff" stroke-width="1" opacity="0.5"/>
            <line x1="-15" y1="5" x2="-8" y2="5" stroke="#ffffff" stroke-width="1" opacity="0.5"/>
            
            <line x1="8" y1="-5" x2="15" y2="-5" stroke="#ffffff" stroke-width="1" opacity="0.4"/>
            <line x1="8" y1="0" x2="15" y2="0" stroke="#ffffff" stroke-width="1" opacity="0.4"/>
            <line x1="8" y1="5" x2="15" y2="5" stroke="#ffffff" stroke-width="1" opacity="0.4"/>
        </g>

        <!-- Texte EMSI avec style moderne -->
        <text x="100" y="125" 
              font-family="'Figtree', 'Arial', sans-serif" 
              font-size="32" 
              font-weight="800"
              text-anchor="middle" 
              fill="url(#grad1)"
              filter="url(#shadow)">
            EMSI
        </text>

        <!-- Sous-texte avec accent -->
        <g>
            <!-- Petite ligne décorative -->
            <line x1="70" y1="140" x2="130" y2="140" stroke="url(#grad2)" stroke-width="2" opacity="0.6"/>
            
            <text x="100" y="153" 
                  font-family="'Figtree', 'Arial', sans-serif" 
                  font-size="11" 
                  font-weight="600"
                  letter-spacing="1"
                  text-anchor="middle" 
                  fill="url(#grad2)">
                EXCELLENCE
            </text>
        </g>

        <!-- Points décoratifs -->
        <circle cx="65" cy="153" r="1.5" fill="url(#grad2)" opacity="0.7"/>
        <circle cx="135" cy="153" r="1.5" fill="url(#grad2)" opacity="0.7"/>
    </svg>
</div>

<!-- Texte de bienvenue amélioré (optionnel - à utiliser sur la page d'accueil) -->
<div class="text-center max-w-3xl mx-auto px-6 mt-8 hidden welcome-text">
    <!-- Badge décoratif -->
    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-full mb-6 border border-indigo-100">
        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <span class="text-sm font-semibold text-indigo-700">Plateforme de Gestion Intégrée</span>
    </div>

    <!-- Titre principal -->
    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 leading-tight">
        Bienvenue sur notre 
        <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            Plateforme Éducative
        </span>
    </h2>

    <!-- Description -->
    <p class="text-gray-600 leading-relaxed text-base md:text-lg mb-6">
        Une solution complète pensée pour répondre à tous les besoins administratifs, pédagogiques, 
        financiers et organisationnels d'un établissement moderne. Centralisez la gestion des inscriptions, 
        le suivi des apprenants, la planification des cours et bien plus encore.
    </p>

    <!-- Caractéristiques clés -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
        <!-- Carte 1 -->
        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mb-4 mx-auto">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Gestion Simplifiée</h3>
            <p class="text-sm text-gray-600">Inscriptions, absences, notes et reporting centralisés</p>
        </div>

        <!-- Carte 2 -->
        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mb-4 mx-auto">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Communication Fluide</h3>
            <p class="text-sm text-gray-600">Coordination optimale entre tous les acteurs</p>
        </div>

        <!-- Carte 3 -->
        <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center mb-4 mx-auto">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Efficacité Maximale</h3>
            <p class="text-sm text-gray-600">Outils avancés pour tous les niveaux</p>
        </div>
    </div>
</div>

<style>
    /* Pour afficher le texte de bienvenue sur certaines pages */
    .show-welcome .welcome-text {
        display: block !important;
    }
</style>