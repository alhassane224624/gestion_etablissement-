@extends('layouts.app-professeur')
@section('title', 'Mon Profil')

@section('content')
<style>
    .profile-container {
        max-width: 850px;
        margin: 0 auto;
        animation: fadeIn .4s ease;
    }
    .profile-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .profile-header {
        background: linear-gradient(135deg,#8b5cf6,#6366f1);
        color: #fff;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .profile-avatar {
        width: 100px; height: 100px;
        border-radius: 50%;
        background: #ede9fe;
        color: #6d28d9;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 700;
        margin: 1rem auto;
    }
    .info-box {
        background: #f9fafb;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .9rem;
        color: #4b5563;
    }
    .form-label { font-weight: 600; color: #374151; }
    .form-control {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        transition: all .2s;
    }
    .form-control:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139,92,246,.2);
    }
    .btn-gradient {
        background: linear-gradient(135deg,#3b82f6,#8b5cf6);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        padding: .6rem 1.4rem;
        transition: .25s;
    }
    .btn-gradient:hover { transform: translateY(-2px); filter: brightness(1.1); }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(10px);} to {opacity:1; transform:none;} }
</style>

<div class="profile-container mt-4">
    <div class="profile-card">
        <div class="profile-header">
            <h4 class="mb-0"><i class="fa-solid fa-user-gear me-2"></i> Profil du Professeur</h4>
            <a href="{{ route('professeur.dashboard') }}" class="btn btn-light btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> Retour
            </a>
        </div>

        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success"><i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}</div>
            @endif

            <div class="text-center mb-4">
                <div class="profile-avatar">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </div>
                <h5 class="fw-bold">{{ Auth::user()->name }}</h5>
                <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
                <span class="badge {{ Auth::user()->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ Auth::user()->is_active ? 'Actif' : 'Inactif' }}
                </span>
                @if(Auth::user()->email_verified_at)
                    <span class="badge bg-info ms-1">
                        Vérifié le {{ Auth::user()->email_verified_at->format('d/m/Y') }}
                    </span>
                @endif
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Adresse e-mail</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Spécialité</label>
                        <input type="text" name="specialite" class="form-control" value="{{ old('specialite', Auth::user()->specialite ?? '') }}" placeholder="Ex: Mathématiques, Informatique...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Statut</label>
                        <input type="text" readonly class="form-control bg-light" value="{{ Auth::user()->is_active ? 'Compte actif' : 'Compte désactivé' }}">
                    </div>

                    <div class="col-12 mt-3">
                        <div class="info-box">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Pour changer votre mot de passe, remplissez les champs ci-dessous.
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Confirmer</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn-gradient">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
