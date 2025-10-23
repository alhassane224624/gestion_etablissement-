@extends('layouts.app-stagiaire')

@section('title', 'Mon Bulletin')
@section('page-title', 'Mon Bulletin Scolaire')

@section('content')
<div class="container-fluid">
    <!-- Filtre période -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('stagiaire.bulletin') }}">
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt me-2"></i>Sélectionner une période
                                </label>
                                <select name="periode_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Toutes les périodes</option>
                                    @foreach($periodes as $periode)
                                        <option value="{{ $periode->id }}" {{ $periodeId == $periode->id ? 'selected' : '' }}>
                                            {{ $periode->nom }} - {{ $periode->anneeScolaire->nom ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des bulletins -->
    @if($bulletins->count() > 0)
        <div class="row g-4">
            @foreach($bulletins as $bulletin)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-2"></i>
                                {{ $bulletin->periode->nom ?? 'N/A' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Année scolaire</small>
                                <p class="mb-0 fw-semibold">
                                    {{ $bulletin->periode->anneeScolaire->nom ?? 'N/A' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Classe</small>
                                <p class="mb-0 fw-semibold">{{ $bulletin->classe->nom ?? 'N/A' }}</p>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Moyenne Générale</small>
                                <h3 class="mb-0 {{ $bulletin->moyenne_generale >= 10 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($bulletin->moyenne_generale ?? 0, 2) }}/20
                                </h3>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Rang</small>
                                <p class="mb-0 fw-semibold">
                                    {{ $bulletin->rang ?? '-' }}/{{ $bulletin->total_classe ?? '-' }}
                                </p>
                            </div>

                            @if($bulletin->validated_at)
                                <div class="alert alert-success border-0 py-2">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <small>Validé le {{ $bulletin->validated_at->format('d/m/Y') }}</small>
                                </div>
                            @else
                                <div class="alert alert-warning border-0 py-2">
                                    <i class="fas fa-clock me-2"></i>
                                    <small>En attente de validation</small>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light border-0">
                            @if($bulletin->validated_at)
                                <a href="{{ route('stagiaire.bulletin.telecharger', $bulletin) }}" 
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-download me-2"></i>Télécharger PDF
                                </a>
                            @else
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="fas fa-lock me-2"></i>Non disponible
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-alt text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">Aucun bulletin disponible</h4>
                        <p class="text-muted">
                            Les bulletins seront disponibles une fois validés par l'administration.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
}
</style>
@endpush
