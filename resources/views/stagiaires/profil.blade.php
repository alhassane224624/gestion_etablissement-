@extends('layouts.app-stagiaire')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <!-- Colonne gauche - Photo et infos principales -->
        <div class="col-lg-4">
            <!-- Photo et statut -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <img src="{{ $stagiaire->photo_url }}" 
                             alt="{{ $stagiaire->nom_complet }}" 
                             class="rounded-circle" 
                             style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #3b82f6;">
                    </div>
                    <h4 class="fw-bold mb-1">{{ $stagiaire->nom }} {{ $stagiaire->prenom }}</h4>
                    <p class="text-muted mb-3">{{ $stagiaire->matricule }}</p>
                    <span class="badge bg-success px-3 py-2">
                        <i class="fas fa-check-circle me-1"></i> {{ $stagiaire->statut_libelle }}
                    </span>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-address-card text-primary me-2"></i>
                        Informations de Contact
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Téléphone</small>
                        <strong>
                            @if($stagiaire->telephone)
                                <i class="fas fa-phone text-success me-1"></i>
                                {{ $stagiaire->telephone }}
                            @else
                                <span class="text-muted">Non renseigné</span>
                            @endif
                        </strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Email</small>
                        <strong>
                            @if($stagiaire->email)
                                <i class="fas fa-envelope text-primary me-1"></i>
                                {{ $stagiaire->email }}
                            @else
                                <span class="text-muted">Non renseigné</span>
                            @endif
                        </strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Adresse</small>
                        <strong>
                            @if($stagiaire->adresse)
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                {{ $stagiaire->adresse }}
                            @else
                                <span class="text-muted">Non renseignée</span>
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Détails -->
        <div class="col-lg-8">
            <!-- Informations personnelles -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user text-primary me-2"></i>
                        Informations Personnelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Date de Naissance</small>
                            <strong>
                                @if($stagiaire->date_naissance)
                                    {{ $stagiaire->date_naissance->format('d/m/Y') }}
                                    <span class="badge bg-info ms-2">{{ $stagiaire->age }} ans</span>
                                @else
                                    <span class="text-muted">Non renseignée</span>
                                @endif
                            </strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Lieu de Naissance</small>
                            <strong>{{ $stagiaire->lieu_naissance ?? 'Non renseigné' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Sexe</small>
                            <strong>
                                @if($stagiaire->sexe == 'M')
                                    <i class="fas fa-mars text-primary me-1"></i> Masculin
                                @elseif($stagiaire->sexe == 'F')
                                    <i class="fas fa-venus text-pink me-1"></i> Féminin
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations scolaires -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-graduation-cap text-success me-2"></i>
                        Informations Scolaires
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Filière</small>
                            <strong class="text-primary">
                                <i class="fas fa-book me-1"></i>
                                {{ $stagiaire->filiere->nom ?? 'N/A' }}
                            </strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Niveau</small>
                            <strong>{{ $stagiaire->niveau->nom ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Classe</small>
                            <strong>{{ $stagiaire->classe->nom ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Date d'Inscription</small>
                            <strong>
                                @if($stagiaire->date_inscription)
                                    {{ $stagiaire->date_inscription->format('d/m/Y') }}
                                @else
                                    Non renseignée
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations tuteur -->
            @if($stagiaire->nom_tuteur || $stagiaire->telephone_tuteur || $stagiaire->email_tuteur)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-users text-warning me-2"></i>
                        Informations du Tuteur
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($stagiaire->nom_tuteur)
                        <div class="col-md-12">
                            <small class="text-muted d-block">Nom</small>
                            <strong>{{ $stagiaire->nom_tuteur }}</strong>
                        </div>
                        @endif
                        @if($stagiaire->telephone_tuteur)
                        <div class="col-md-6">
                            <small class="text-muted d-block">Téléphone</small>
                            <strong>
                                <i class="fas fa-phone text-success me-1"></i>
                                {{ $stagiaire->telephone_tuteur }}
                            </strong>
                        </div>
                        @endif
                        @if($stagiaire->email_tuteur)
                        <div class="col-md-6">
                            <small class="text-muted d-block">Email</small>
                            <strong>
                                <i class="fas fa-envelope text-primary me-1"></i>
                                {{ $stagiaire->email_tuteur }}
                            </strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Besoin de modifier vos informations ?
                    </h6>
                    <p class="text-muted mb-0">
                        Pour toute modification de vos informations personnelles, veuillez contacter l'administration 
                        via la <a href="{{ route('messages.index') }}" class="text-primary">messagerie</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.text-pink {
    color: #ec4899;
}
</style>
@endpush
@endsection