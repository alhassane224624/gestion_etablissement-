@extends('layouts.app-stagiaire')

@section('title', 'Mon Emploi du Temps')
@section('page-title', 'Mon Emploi du Temps')

@section('content')
<div class="container-fluid">
    @if(isset($message))
        <div class="alert alert-info border-0 shadow-sm">
            <i class="fas fa-info-circle me-2"></i>{{ $message }}
        </div>
    @else
        <!-- Sélecteur de date -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('stagiaire.emploi-du-temps') }}">
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Sélectionner une date</label>
                                    <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-2"></i>Afficher
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation de dates -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('stagiaire.emploi-du-temps') }}?date={{ \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d') }}" 
                       class="btn btn-outline-primary">
                        <i class="fas fa-chevron-left me-2"></i>Jour précédent
                    </a>
                    <a href="{{ route('stagiaire.emploi-du-temps') }}?date={{ now()->format('Y-m-d') }}" 
                       class="btn btn-primary">
                        <i class="fas fa-calendar-day me-2"></i>Aujourd'hui
                    </a>
                    <a href="{{ route('stagiaire.emploi-du-temps') }}?date={{ \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d') }}" 
                       class="btn btn-outline-primary">
                        Jour suivant<i class="fas fa-chevron-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Emploi du temps -->
        @if($plannings->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-calendar-day text-primary me-2"></i>
                                {{ \Carbon\Carbon::parse($date)->isoFormat('dddd D MMMM YYYY') }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="timeline">
                                @foreach($plannings as $cours)
                                    <div class="row mb-4">
                                        <div class="col-auto">
                                            <div class="badge bg-primary px-4 py-3 text-center" style="min-width: 90px;">
                                                <div class="fw-bold fs-5">{{ $cours->heure_debut }}</div>
                                                <div class="small">{{ $cours->heure_fin }}</div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card border-start border-4 border-primary shadow-sm">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div>
                                                            <h4 class="mb-2 fw-bold">{{ $cours->matiere->nom ?? 'N/A' }}</h4>
                                                            <span class="badge bg-secondary text-uppercase">
                                                                {{ $cours->type_cours }}
                                                            </span>
                                                        </div>
                                                        <span class="badge bg-{{ $cours->statut_color }} fs-6">
                                                            {{ $cours->statut_libelle }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-chalkboard-teacher text-primary me-3 fa-lg"></i>
                                                                <div>
                                                                    <small class="text-muted d-block">Professeur</small>
                                                                    <strong>{{ $cours->professeur->name ?? 'N/A' }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-door-open text-success me-3 fa-lg"></i>
                                                                <div>
                                                                    <small class="text-muted d-block">Salle</small>
                                                                    <strong>{{ $cours->salle->nom ?? 'N/A' }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if($cours->description)
                                                        <div class="mt-3 pt-3 border-top">
                                                            <div class="alert alert-light mb-0">
                                                                <i class="fas fa-info-circle text-info me-2"></i>
                                                                <strong>Note :</strong> {{ $cours->description }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="mt-3">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Durée : {{ $cours->duree_heures }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">Aucun cours prévu</h4>
                            <p class="text-muted mb-0">Vous n'avez aucun cours prévu pour cette journée.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Légende -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Légende des Types de Cours
                        </h6>
                        <div class="row">
                            <div class="col-md-3">
                                <span class="badge bg-primary">COURS</span> Cours magistral
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-success">TD</span> Travaux dirigés
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-info">TP</span> Travaux pratiques
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-danger">EXAMEN</span> Examen
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection