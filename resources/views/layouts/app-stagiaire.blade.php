<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Espace Stagiaire')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary:#3b82f6;
      --sidebar-width:260px;
      --sidebar-collapsed:70px;
    }

    body {
      margin: 0;
      font-family: 'Inter', system-ui, sans-serif;
      background-color: #f1f5f9;
      overflow-x: hidden;
    }

    /* ===== Sidebar ===== */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: linear-gradient(135deg, var(--primary) 0%, #60a5fa 100%);
      box-shadow: 2px 0 12px rgba(0,0,0,0.1);
      color: white;
      display: flex;
      flex-direction: column;
      z-index: 1000;
      overflow-y: auto;
    }

    .sidebar-header {
      padding: 1.5rem;
      text-align: center;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar .nav-link {
      color: rgba(255,255,255,0.9);
      padding: .75rem 1.25rem;
      margin: .25rem .75rem;
      border-radius: .5rem;
      display: flex;
      align-items: center;
      transition: all .3s;
    }

    .sidebar .nav-link i {
      width: 20px;
      margin-right: 10px;
      text-align: center;
    }

    .sidebar .nav-link:hover {
      background: rgba(255,255,255,0.15);
      transform: translateX(5px);
    }

    .sidebar .nav-link.active {
      background: rgba(255,255,255,0.25);
      font-weight: 600;
    }

    .section-title {
      font-size: .75rem;
      letter-spacing: .5px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.6);
      padding: .5rem 1.25rem;
      margin-top: 1rem;
      font-weight: 600;
    }

    .sidebar-footer {
      margin-top: auto;
      padding: 1rem;
      border-top: 1px solid rgba(255,255,255,0.1);
    }

    /* ===== Main ===== */
    main {
      margin-left: var(--sidebar-width);
      padding: 2rem;
      min-height: 100vh;
      background-color: #f8fafc;
      transition: margin-left 0.3s ease;
    }

    .navbar {
      background: #fff;
      border-radius: .75rem;
      box-shadow: 0 1px 4px rgba(0,0,0,.1);
      padding: .75rem 1.25rem;
    }

    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
      .sidebar {
        width: var(--sidebar-collapsed);
      }
      main {
        margin-left: var(--sidebar-collapsed);
        padding: 1rem;
      }
      .sidebar .nav-link span,
      .sidebar-header small,
      .section-title {
        display: none;
      }
    }

  </style>

  @stack('styles')
</head>
<body>
  <nav class="sidebar">
    <div class="sidebar-header">
      <i class="fas fa-user-graduate fa-2x mb-2"></i>
      <h5 class="fw-bold mb-1">Espace Stagiaire</h5>
      <small class="text-white-50">{{ Auth::user()->name }}</small>
    </div>

    <ul class="nav flex-column mt-3">
      <li class="nav-item">
        <a href="{{ route('stagiaire.dashboard') }}" class="nav-link {{ Request::is('stagiaire/dashboard') ? 'active' : '' }}">
          <i class="fas fa-home"></i><span> Accueil</span>
        </a>
      </li>

      <div class="section-title">Scolarité</div>
      <li class="nav-item">
        <a href="{{ route('stagiaire.notes') }}" class="nav-link {{ Request::is('stagiaire/notes*') ? 'active' : '' }}">
          <i class="fas fa-chart-line"></i><span> Mes Notes</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('stagiaire.bulletin') }}" class="nav-link {{ Request::is('stagiaire/bulletin*') ? 'active' : '' }}">
          <i class="fas fa-file-alt"></i><span> Mon Bulletin</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('stagiaire.emploi-du-temps') }}" class="nav-link {{ Request::is('stagiaire/emploi-du-temps*') ? 'active' : '' }}">
          <i class="fas fa-calendar-alt"></i><span> Emploi du Temps</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('stagiaire.absences') }}" class="nav-link {{ Request::is('stagiaire/absences*') ? 'active' : '' }}">
          <i class="fas fa-calendar-times"></i><span> Mes Absences</span>
        </a>
      </li>

      <div class="section-title">Communication</div>
      <li class="nav-item">
        <a href="{{ route('messages.index') }}" class="nav-link {{ Request::is('messages*') ? 'active' : '' }}">
          <i class="fas fa-envelope"></i><span> Messages</span>
          @if(Auth::user()->getUnreadMessagesCount() > 0)
            <span class="badge bg-danger position-absolute top-0 end-0 translate-middle badge-notification">
              {{ Auth::user()->getUnreadMessagesCount() }}
            </span>
          @endif
        </a>
      </li>

      <div class="section-title">Mon compte</div>
      <li class="nav-item">
        <a href="{{ route('stagiaire.profil') }}" class="nav-link {{ Request::is('stagiaire/profil*') ? 'active' : '' }}">
          <i class="fas fa-user-circle"></i><span> Mon Profil</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light w-100">
          <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
        </button>
      </form>
    </div>
  </nav>

  <main>
    <nav class="navbar mb-4">
      <div class="container-fluid">
        <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
        <div class="d-flex align-items-center">
          <span class="badge bg-info me-3">Stagiaire</span>
          <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('stagiaire.profil') }}"><i class="fas fa-user-edit me-2"></i>Mon Profil</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        <strong>Erreurs :</strong>
        <ul class="mb-0 mt-2">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
