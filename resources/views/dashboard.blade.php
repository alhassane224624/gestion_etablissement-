@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title text-center mb-4">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </h1>
            
            <div class="alert alert-primary text-center" role="alert">
                Bienvenue, <strong>{{ auth()->user()->name }}</strong> ! 👋
            </div>

            @if (auth()->user()->isAdmin())
                <p class="text-center">
                    Vous êtes un <strong>administrateur</strong>. Vous pouvez gérer les filières et les stagiaires.
                </p>

                <div class="row justify-content-center">
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('filieres.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-layer-group"></i> Gérer les filières
                        </a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('stagiaires.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-users"></i> Gérer les stagiaires
                        </a>
                    </div>
                </div>
            @else
                <p class="text-center">
                    Vous êtes un <strong>utilisateur</strong>. Vous pouvez consulter les filières et les stagiaires.
                </p>

                <div class="row justify-content-center">
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('filieres.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-eye"></i> Voir les filières
                        </a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('stagiaires.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-eye"></i> Voir les stagiaires
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
