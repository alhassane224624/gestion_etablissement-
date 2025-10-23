@extends('layouts.app-stagiaire')

@section('title', 'Mes Notes')
@section('page-title', 'Mes Notes')

@section('content')
<div class="container-fluid">
    <!-- Statistiques en haut -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Moyenne Générale</h6>
                    <h2 class="fw-bold mb-0 {{ $moyenneGenerale >= 10 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($moyenneGenerale, 2) }}/20
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Notes</h6>
                    <h2 class="fw-bold mb-0 text-primary">{{ $notes->total() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Matières Suivies</h6>
                    <h2 class="fw-bold mb-0 text-info">{{ $notesParMatiere->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-filter text-primary me-2"></i>
                        Filtres
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('stagiaire.notes') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Période</label>
                                <select name="periode_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Toutes les périodes</option>
                                    @foreach($periodes as $periode)
                                        <option value="{{ $periode->id }}" {{ $periodeId == $periode->id ? 'selected' : '' }}>
                                            {{ $periode->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Matière</label>
                                <select name="matiere_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Toutes les matières</option>
                                    @foreach($matieres as $matiere)
                                        <option value="{{ $matiere->id }}" {{ $matiereId == $matiere->id ? 'selected' : '' }}>
                                            {{ $matiere->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search me-2"></i>Filtrer
                                    </button>
                                    <a href="{{ route('stagiaire.notes') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                    <a href="{{ route('stagiaire.notes.telecharger', request()->query()) }}" 
                                       class="btn btn-success">
                                        <i class="fas fa-file-pdf me-2"></i>PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes par matière -->
    @if($notesParMatiere->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-book text-primary me-2"></i>
                        Moyennes par Matière
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($notesParMatiere as $matiereData)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-start border-4 {{ $matiereData['moyenne'] >= 10 ? 'border-success' : 'border-danger' }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $matiereData['matiere']->nom }}</h6>
                                                <small class="text-muted">{{ $matiereData['count'] }} note(s)</small>
                                            </div>
                                            <div class="text-end">
                                                <div class="fs-3 fw-bold {{ $matiereData['moyenne'] >= 10 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($matiereData['moyenne'], 2) }}
                                                </div>
                                                <small class="text-muted">/20</small>
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
    @endif

    <!-- Liste détaillée des notes -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-list text-primary me-2"></i>
                        Détail des Notes
                    </h5>
                </div>
                <div class="card-body">
                    @if($notes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Matière</th>
                                        <th>Type</th>
                                        <th class="text-center">Note</th>
                                        <th class="text-center">Note/20</th>
                                        <th>Commentaire</th>
                                        <th>Professeur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notes as $note)
                                        <tr>
                                            <td>
                                                <small>{{ $note->created_at->format('d/m/Y') }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $note->matiere->nom ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $note->type_note === 'examen' ? 'danger' : 'info' }} text-uppercase">
                                                    {{ $note->type_note }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge fs-6 {{ $note->note >= 10 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ number_format($note->note, 2) }}/{{ $note->note_sur }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <strong class="{{ $note->note_sur_20 >= 10 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($note->note_sur_20, 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($note->commentaire, 40) ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $note->creator->name ?? 'N/A' }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notes->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune note trouvée</h5>
                            <p class="text-muted mb-0">
                                @if($periodeId || $matiereId)
                                    Aucune note ne correspond à vos critères de recherche.
                                @else
                                    Vous n'avez pas encore de notes enregistrées.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection