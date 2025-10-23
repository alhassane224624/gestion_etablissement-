<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Professeur')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --primary: #8b5cf6;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f8fafc;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, var(--primary) 0%, #a78bfa 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.25rem;
            margin: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            font-weight: 600;
        }
        
        main {
            margin-left: 250px;
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
        }
        
        .btn-primary:hover {
            background: #7c3aed;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
        }
        
        .sidebar-footer {
            position: sticky;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary) 0%, #a78bfa 100%);
            padding: 1rem;
            margin-top: auto;
        }

        .badge-notification {
            position: absolute;
            top: 5px;
            right: 10px;
            background: #ef4444;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <nav class="sidebar px-0">
                <div class="d-flex flex-column h-100">
                    <div class="text-center mb-4 pt-3">
                        <h4 class="text-white fw-bold">
                            <i class="fas fa-chalkboard-teacher"></i> Espace Prof
                        </h4>
                        <small class="text-white-50">{{ Auth::user()->name }}</small>
                    </div>
                    
                    <ul class="nav flex-column flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('professeur/dashboard') ? 'active' : '' }}" href="{{ route('professeur.dashboard') }}">
                                <i class="fas fa-home me-2"></i> Accueil
                            </a>
                        </li>
                        
                        <li class="nav-item mt-3">
                            <small class="text-white-50 px-3">MON ESPACE</small>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('professeur/stagiaires*') ? 'active' : '' }}" href="{{ route('professeur.stagiaires') }}">
                                <i class="fas fa-user-graduate me-2"></i> Mes Stagiaires
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('professeur/presences*') ? 'active' : '' }}" href="{{ route('professeur.presences') }}">
                                <i class="fas fa-check-circle me-2"></i> Présences
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('professeur/notes*') ? 'active' : '' }}" href="{{ route('professeur.notes-par-matiere') }}">
                                <i class="fas fa-clipboard-list me-2"></i> Mes Notes
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('professeur/planning*') ? 'active' : '' }}" href="{{ route('professeur.planning') }}">
                                <i class="fas fa-calendar-alt me-2"></i> Mon Planning
                            </a>
                        </li>
                        
                        <li class="nav-item mt-3">
                            <small class="text-white-50 px-3">COMMUNICATION</small>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link position-relative {{ Request::is('messages*') ? 'active' : '' }}" href="{{ route('messages.index') }}">
                                <i class="fas fa-envelope me-2"></i> Messages
                                @php
                                    $unreadCount = \App\Models\Message::where('receiver_id', Auth::id())
                                        ->where('is_read', false)
                                        ->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="badge badge-notification">{{ $unreadCount }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <li class="nav-item mt-3">
                            <small class="text-white-50 px-3">EXPORTS</small>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('professeur.notes.export-pdf') }}">
                                <i class="fas fa-file-pdf me-2"></i> Notes PDF
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('professeur.notes.export-excel') }}">
                                <i class="fas fa-file-excel me-2"></i> Notes Excel
                            </a>
                        </li>
                        
                        <li class="nav-item mt-3">
                            <small class="text-white-50 px-3">MON COMPTE</small>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('profile*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-cog me-2"></i> Mon Profil
                            </a>
                        </li>
                    </ul>
                    
                    <div class="sidebar-footer">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
            
            <main class="px-md-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 mt-3 rounded">
                    <div class="container-fluid">
                        <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary me-3">Professeur</span>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit me-2"></i> Profil
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Erreurs :</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });
        
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Auto-refresh pour les messages non lus
        setInterval(function() {
            fetch('{{ route("messages.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.badge-notification');
                    if (data.count > 0) {
                        if (badge) {
                            badge.textContent = data.count;
                            badge.style.display = 'inline-block';
                        } else {
                            // Créer le badge s'il n'existe pas
                            const messagesLink = document.querySelector('a[href="{{ route("messages.index") }}"]');
                            if (messagesLink && !messagesLink.querySelector('.badge-notification')) {
                                const newBadge = document.createElement('span');
                                newBadge.className = 'badge badge-notification';
                                newBadge.textContent = data.count;
                                messagesLink.appendChild(newBadge);
                            }
                        }
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.log('Erreur lors de la récupération des messages:', error));
        }, 30000); // Toutes les 30 secondes
    </script>
    
    @stack('scripts')
</body>
</html>