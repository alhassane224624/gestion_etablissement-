<svg {{ $attributes }} viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <!-- Gradients -->
        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:rgb(99,102,241);stop-opacity:1">
                <animate attributeName="stop-color" values="rgb(99,102,241);rgb(139,92,246);rgb(99,102,241)" dur="3s" repeatCount="indefinite"/>
            </stop>
            <stop offset="100%" style="stop-color:rgb(139,92,246);stop-opacity:1">
                <animate attributeName="stop-color" values="rgb(139,92,246);rgb(99,102,241);rgb(139,92,246)" dur="3s" repeatCount="indefinite"/>
            </stop>
        </linearGradient>
        
        <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:rgb(245,158,11);stop-opacity:1"/>
            <stop offset="100%" style="stop-color:rgb(251,191,36);stop-opacity:1"/>
        </linearGradient>

        <!-- Filtre d'ombre -->
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
    </defs>

    <!-- Cercle de fond pulsant -->
    <circle cx="100" cy="100" r="90" fill="url(#grad1)" opacity="0.1">
        <animate attributeName="r" values="85;95;85" dur="2s" repeatCount="indefinite"/>
        <animate attributeName="opacity" values="0.1;0.2;0.1" dur="2s" repeatCount="indefinite"/>
    </circle>

    <!-- Livre ouvert (gauche) -->
    <g filter="url(#shadow)">
        <path d="M 60 70 Q 60 50, 80 50 L 95 50 L 95 130 L 80 130 Q 60 130, 60 110 Z" 
              fill="url(#grad1)" 
              stroke="url(#grad2)" 
              stroke-width="2">
            <animate attributeName="d" 
                     values="M 60 70 Q 60 50, 80 50 L 95 50 L 95 130 L 80 130 Q 60 130, 60 110 Z;
                             M 60 70 Q 60 48, 80 48 L 95 48 L 95 132 L 80 132 Q 60 132, 60 110 Z;
                             M 60 70 Q 60 50, 80 50 L 95 50 L 95 130 L 80 130 Q 60 130, 60 110 Z"
                     dur="2s" 
                     repeatCount="indefinite"/>
        </path>
        
        <!-- Lignes de texte sur le livre gauche -->
        <line x1="70" y1="70" x2="90" y2="70" stroke="url(#grad2)" stroke-width="2" opacity="0.8"/>
        <line x1="70" y1="80" x2="90" y2="80" stroke="url(#grad2)" stroke-width="2" opacity="0.8"/>
        <line x1="70" y1="90" x2="90" y2="90" stroke="url(#grad2)" stroke-width="2" opacity="0.8"/>
    </g>

    <!-- Livre ouvert (droit) -->
    <g filter="url(#shadow)">
        <path d="M 140 70 Q 140 50, 120 50 L 105 50 L 105 130 L 120 130 Q 140 130, 140 110 Z" 
              fill="url(#grad1)" 
              stroke="url(#grad2)" 
              stroke-width="2">
            <animate attributeName="d" 
                     values="M 140 70 Q 140 50, 120 50 L 105 50 L 105 130 L 120 130 Q 140 130, 140 110 Z;
                             M 140 70 Q 140 48, 120 48 L 105 48 L 105 132 L 120 132 Q 140 132, 140 110 Z;
                             M 140 70 Q 140 50, 120 50 L 105 50 L 105 130 L 120 130 Q 140 130, 140 110 Z"
                     dur="2s" 
                     repeatCount="indefinite"/>
        </path>
        
        <!-- Lignes de texte sur le livre droit -->
        <line x1="110" y1="70" x2="130" y2="70" stroke="url(#grad2)" stroke-width="2" opacity="0.8"/>
        <line x1="110" y1="80" x2="130" y2="80" stroke="url(#grad2)" stroke-width="2" opacity="0.8"/>
        <line x1="110" y1="90" x2="130" y2="90" stroke="url(#grad2)" stroke-width="2" opacity="0.8"/>
    </g>

    <!-- Reliure centrale -->
    <rect x="97" y="48" width="6" height="84" fill="url(#grad2)" rx="2">
        <animate attributeName="height" values="84;86;84" dur="2s" repeatCount="indefinite"/>
        <animate attributeName="y" values="48;47;48" dur="2s" repeatCount="indefinite"/>
    </rect>

    <!-- Étoile brillante en haut -->
    <g transform="translate(100, 35)">
        <path d="M 0,-8 L 2,-2 L 8,0 L 2,2 L 0,8 L -2,2 L -8,0 L -2,-2 Z" 
              fill="url(#grad2)">
            <animateTransform attributeName="transform" 
                              type="rotate" 
                              from="0" 
                              to="360" 
                              dur="4s" 
                              repeatCount="indefinite"/>
            <animate attributeName="opacity" values="1;0.5;1" dur="1.5s" repeatCount="indefinite"/>
        </path>
    </g>

    <!-- Particules flottantes -->
    <circle cx="40" cy="60" r="2" fill="url(#grad2)" opacity="0.6">
        <animate attributeName="cy" values="60;50;60" dur="3s" repeatCount="indefinite"/>
        <animate attributeName="opacity" values="0.6;0.2;0.6" dur="3s" repeatCount="indefinite"/>
    </circle>
    
    <circle cx="160" cy="80" r="2" fill="url(#grad2)" opacity="0.6">
        <animate attributeName="cy" values="80;70;80" dur="2.5s" repeatCount="indefinite"/>
        <animate attributeName="opacity" values="0.6;0.2;0.6" dur="2.5s" repeatCount="indefinite"/>
    </circle>
    
    <circle cx="50" cy="120" r="2" fill="url(#grad2)" opacity="0.6">
        <animate attributeName="cy" values="120;110;120" dur="3.5s" repeatCount="indefinite"/>
        <animate attributeName="opacity" values="0.6;0.2;0.6" dur="3.5s" repeatCount="indefinite"/>
    </circle>

    <!-- Texte EMSI -->
    <text x="100" y="160" 
          font-family="Arial, sans-serif" 
          font-size="28" 
          font-weight="bold" 
          text-anchor="middle" 
          fill="url(#grad1)"
          filter="url(#shadow)">
        EMSI
        <animate attributeName="opacity" values="1;0.7;1" dur="2s" repeatCount="indefinite"/>
    </text>

    <!-- Sous-titre -->
    <text x="100" y="178" 
          font-family="Arial, sans-serif" 
          font-size="10" 
          text-anchor="middle" 
          fill="url(#grad2)">
        Excellence · Innovation
    </text>

    <!-- Cercle extérieur tournant -->
    <circle cx="100" cy="100" r="95" 
            fill="none" 
            stroke="url(#grad2)" 
            stroke-width="2" 
            opacity="0.3"
            stroke-dasharray="10 5">
        <animateTransform attributeName="transform" 
                          type="rotate" 
                          from="0 100 100" 
                          to="360 100 100" 
                          dur="20s" 
                          repeatCount="indefinite"/>
    </circle>
</svg>