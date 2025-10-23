@extends('layouts.app')

@section('title', 'Gestion des Notes')
@section('page-title', 'Gestion des Notes')

@section('content')
    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-icon mx-auto mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="mb-0">{{ $notes->total() }}</h3>
                <p class="text-muted mb-0">Notes au Total</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-icon mx-auto mb-2" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-chart-line"></i>
                </div>
                @php
                    $moyenneGenerale = $notes->avg('note') ?? 0;
                @endphp
                <h3 class="mb-0 {{ $moyenneGenerale >= 10 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($moyenneGenerale, 2) }}/20
                </h3>
                <p class="text-muted mb-0">Moyenne Générale</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-icon mx-auto mb-2" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="mb-0">{{ $matieres->count() }}</h3>
                <p class="text-muted mb-0">Matières</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card text-center">
                <div class="stat-icon mx-auto mb-2" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="fas fa-percentage"></i>
                </div>
                @php
                    $totalNotes = $notes->count();
                    $notesReussies = $notes->where('note', '>=', 10)->count();
                    $tauxReussite = $totalNotes > 0 ? ($notesReussies / $totalNotes) * 100 : 0;
                @endphp
                <h3 class="mb-0 {{ $tauxReussite >= 50 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($tauxReussite, 1) }}%
                </h3>
                <p class="text-muted mb-0">Taux de Réussite</p>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="row mb-4">
        <div class="col-lg-9">
            <div class="action-card">
                <h5 class="mb-3">
                    <i class="fas fa-filter me-2 text-primary"></i>Filtres de Recherche
                </h5>
                <form method="GET" action="{{ route('notes.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Classe</label>
                            <select name="classe_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Toutes les classes</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Matière</label>
                            <select name="matiere_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Toutes les matières</option>
                                @foreach($matieres as $matiere)
                                    <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                        {{ $matiere->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Période</label>
                            <select name="periode_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Toutes les périodes</option>
                                @foreach($periodes as $periode)
                                    <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                        {{ $periode->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Type de note</label>
                            <select name="type_note" class="form-select" onchange="this.form.submit()">
                                <option value="">Tous les types</option>
                                <option value="ds" {{ request('type_note') == 'ds' ? 'selected' : '' }}>Devoir Surveillé</option>
                                <option value="cc" {{ request('type_note') == 'cc' ? 'selected' : '' }}>Contrôle Continu</option>
                                <option value="examen" {{ request('type_note') == 'examen' ? 'selected' : '' }}>Examen</option>
                                <option value="tp" {{ request('type_note') == 'tp' ? 'selected' : '' }}>Travaux Pratiques</option>
                                <option value="projet" {{ request('type_note') == 'projet' ? 'selected' : '' }}>Projet</option>
                            </select>
                        </div>
                    </div>
                    @if(request()->hasAny(['classe_id', 'matiere_id', 'periode_id', 'type_note']))
                        <div class="mt-3">
                            <a href="{{ route('notes.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Réinitialiser les filtres
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="action-card text-center h-100">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-tools me-2"></i>Actions Rapides
                </h6>
                <div class="d-grid gap-2">
                    @if(Auth::user()->isProfesseur())
                        <a href="{{ route('notes.create') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Nouvelle Note
                        </a>
                    @else
                        <div class="alert alert-info mb-0 py-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Seuls les professeurs peuvent ajouter des notes
                            </small>
                        </div>
                    @endif
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Imprimer
                    </button>
                    <button class="btn btn-outline-success" onclick="exportNotes()">
                        <i class="fas fa-file-excel me-2"></i>Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des notes -->
    <div class="action-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-list-ul me-2 text-primary"></i>Liste des Notes
                @if(request()->hasAny(['classe_id', 'matiere_id', 'periode_id', 'type_note']))
                    <span class="badge bg-info ms-2">Résultats Filtrés</span>
                @endif
            </h5>
            <div class="text-muted small">
                <i class="fas fa-database me-1"></i>
                {{ $notes->total() }} note(s) au total
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 20%">Stagiaire</th>
                        <th style="width: 12%">Classe</th>
                        <th style="width: 15%">Matière</th>
                        <th style="width: 10%">Type</th>
                        <th style="width: 10%">Période</th>
                        <th class="text-center" style="width: 10%">Note</th>
                        <th class="text-center" style="width: 10%">Appréciation</th>
                        <th style="width: 10%">Professeur</th>
                        <th class="text-center" style="width: 8%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notes as $index => $note)
                        <tr>
                            <td class="fw-bold text-muted">{{ $notes->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($note->stagiaire->photo)
                                        <img src="{{ asset('storage/' . $note->stagiaire->photo) }}" 
                                             class="rounded-circle me-2" width="35" height="35" alt="Photo">
                                    @else
                                        <div class="bg-gradient rounded-circle me-2 d-flex align-items-center justify-center" 
                                             style="width: 35px; height: 35px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="fas fa-user text-white" style="font-size: 14px;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $note->stagiaire->nom }} {{ $note->stagiaire->prenom }}</div>
                                        <small class="text-muted">{{ $note->stagiaire->matricule }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($note->classe)
                                    <span class="badge bg-secondary">{{ $note->classe->nom }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $note->matiere->nom }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-star text-warning"></i> Coef: {{ $note->matiere->coefficient }}
                                </small>
                            </td>
                            <td>
                                @php
                                    $typeColors = [
                                        'examen' => 'danger',
                                        'ds' => 'warning',
                                        'cc' => 'info',
                                        'tp' => 'success',
                                        'projet' => 'primary'
                                    ];
                                    $color = $typeColors[$note->type_note] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ strtoupper($note->type_note) }}
                                </span>
                            </td>
                            <td>
                                @if($note->periode)
                                    <small>{{ $note->periode->nom }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge bg-{{ $note->note >= 16 ? 'success' : ($note->note >= 10 ? 'primary' : 'danger') }} fs-6 mb-1">
                                        {{ number_format($note->note, 2) }}/{{ $note->note_sur }}
                                    </span>
                                    @if($note->note_sur != 20)
                                        <small class="text-muted">({{ number_format($note->note_sur_20, 2) }}/20)</small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $appreciationColors = [
                                        'Excellent' => 'success',
                                        'Très bien' => 'info',
                                        'Bien' => 'primary',
                                        'Assez bien' => 'warning',
                                        'Passable' => 'secondary',
                                        'Insuffisant' => 'danger'
                                    ];
                                    $appColor = $appreciationColors[$note->appreciation] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $appColor }}">
                                    {{ $note->appreciation }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-tie text-primary me-2"></i>
                                    <div>
                                        <small class="fw-bold">{{ $note->creator->name ?? 'N/A' }}</small><br>
                                        <small class="text-muted">{{ $note->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('notes.show', $note) }}" 
                                       class="btn btn-outline-info" 
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(Auth::user()->isProfesseur() && $note->created_by === Auth::id())
                                        <a href="{{ route('notes.edit', $note) }}" 
                                           class="btn btn-outline-warning" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                onclick="deleteNote({{ $note->id }})" 
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <h5>Aucune note trouvée</h5>
                                    <p>
                                        @if(request()->hasAny(['classe_id', 'matiere_id', 'periode_id', 'type_note']))
                                            Aucun résultat ne correspond à vos critères de recherche.
                                        @else
                                            Commencez par ajouter des notes aux stagiaires.
                                        @endif
                                    </p>
                                    @if(Auth::user()->isProfesseur())
                                        <a href="{{ route('notes.create') }}" class="btn btn-primary mt-3">
                                            <i class="fas fa-plus-circle me-2"></i>Ajouter la première note
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($notes->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $notes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@push('styles')
<style>
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        color: white;
        font-size: 24px;
    }
    
    .table thead th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }
    
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }
    
    @media print {
        .action-card:first-child,
        .btn-group,
        .stat-card {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function deleteNote(noteId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette note ?\n\nCette action est irréversible.')) {
        fetch(`/notes/${noteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression de la note');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la suppression');
        });
    }
}

function exportNotes() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '/notes/export?' + params.toString();
}
</script>
@endpush