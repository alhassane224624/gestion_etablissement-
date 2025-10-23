@extends('layouts.app')

@section('title', 'Liste des Stagiaires')
@section('page-title', 'Liste des Stagiaires')

@section('content')
<style>
/* ====== STYLE GLOBAL ====== */
body {
    background: linear-gradient(135deg, #f9fafb, #eef2ff);
    font-family: 'Poppins', sans-serif;
    color: #1e293b;
}

.fade-in {
    opacity: 0;
    transform: translateY(10px);
    animation: fadeIn 0.6s ease forwards;
}
@keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }

/* ====== CARTES ====== */
.card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
    transition: transform .25s ease, box-shadow .25s ease;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.07);
}

/* ====== BOUTONS ====== */
.btn-main, .btn-green, .btn-gray {
    border-radius: 10px;
    padding: .5rem 1.2rem;
    font-weight: 600;
    transition: all .3s ease;
    font-size: 0.95rem;
}
.btn-main {
    background: linear-gradient(90deg, #4f46e5, #6366f1);
    color: white;
}
.btn-main:hover { background: linear-gradient(90deg, #4338ca, #4f46e5); transform: translateY(-1px); }

.btn-green {
    background: linear-gradient(90deg, #16a34a, #22c55e);
    color: white;
}
.btn-green:hover { background: linear-gradient(90deg, #15803d, #16a34a); transform: translateY(-1px); }

.btn-gray {
    background: #f3f4f6;
    color: #374151;
}
.btn-gray:hover { background: #e5e7eb; }

/* ====== TABLE ====== */
.table thead {
    background: linear-gradient(90deg, #e0e7ff, #eef2ff);
}
th {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #475569;
}
.table tr:hover {
    background-color: #f9fafb;
}
td {
    vertical-align: middle;
}

/* ====== FILTRES ====== */
.filter-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
}
input, select {
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    font-size: 0.9rem;
}

/* ====== IMAGES ====== */
.stagiaire-img {
    height: 42px;
    width: 42px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #c7d2fe;
}

/* ====== STATS ====== */
.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    transition: transform .2s ease;
}
.stat-card:hover { transform: translateY(-3px); }
.stat-icon {
    border-radius: 14px;
    color: white;
    font-size: 1.4rem;
    padding: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-text {
    margin-left: 0.9rem;
}
.stat-label {
    color: #6b7280;
    font-size: 0.9rem;
    font-weight: 600;
}
.stat-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #111827;
}
</style>

<div class="fade-in space-y-6">

    <!-- === HEADER === -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <h1 class="fw-bold fs-3 text-dark mb-0">
            <i class="fas fa-users text-primary"></i> Liste des Stagiaires
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('stagiaires.export', request()->query()) }}" class="btn-green">
                <i class="fas fa-file-excel me-1"></i> Exporter Excel
            </a>
            <a href="{{ route('stagiaires.create') }}" class="btn-main">
                <i class="fas fa-plus me-1"></i> Nouveau Stagiaire
            </a>
        </div>
    </div>

    <!-- === ALERTES === -->
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <!-- === FILTRES === -->
    <div class="card p-4">
        <form method="GET" action="{{ route('stagiaires.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="filter-label mb-1">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, prénom, matricule..." class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="filter-label mb-1">Filière</label>
                    <select name="filiere_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ request('filiere_id') == $filiere->id ? 'selected' : '' }}>{{ $filiere->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="filter-label mb-1">Classe</label>
                    <select name="classe_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="filter-label mb-1">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        <option value="diplome" {{ request('statut') == 'diplome' ? 'selected' : '' }}>Diplômé</option>
                        <option value="abandonne" {{ request('statut') == 'abandonne' ? 'selected' : '' }}>Abandonné</option>
                        <option value="transfere" {{ request('statut') == 'transfere' ? 'selected' : '' }}>Transféré</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn-main"><i class="fas fa-search me-1"></i> Filtrer</button>
                <a href="{{ route('stagiaires.index') }}" class="btn-gray"><i class="fas fa-redo me-1"></i> Réinitialiser</a>
            </div>
        </form>
    </div>

    <!-- === TABLEAU === -->
    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Matricule</th>
                        <th>Nom & Prénom</th>
                        <th>Filière</th>
                        <th>Classe</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stagiaires as $stagiaire)
                        <tr>
                            <td><img src="{{ $stagiaire->photo_url }}" alt="{{ $stagiaire->nom_complet }}" class="stagiaire-img"></td>
                            <td>{{ $stagiaire->matricule }}</td>
                            <td><strong>{{ $stagiaire->nom }}</strong> <span class="text-muted">{{ $stagiaire->prenom }}</span></td>
                            <td>{{ $stagiaire->filiere->nom ?? '—' }}</td>
                            <td>{{ $stagiaire->classe->nom ?? '—' }}</td>
                            <td>
                                @php
                                    $colors = [
                                        'actif' => 'bg-success-subtle text-success fw-semibold',
                                        'suspendu' => 'bg-warning-subtle text-warning fw-semibold',
                                        'diplome' => 'bg-primary-subtle text-primary fw-semibold',
                                        'abandonne' => 'bg-danger-subtle text-danger fw-semibold',
                                        'transfere' => 'bg-purple-100 text-purple fw-semibold',
                                    ];
                                @endphp
                                <span class="badge px-2 py-1 rounded-pill {{ $colors[$stagiaire->statut] ?? 'bg-light text-dark' }}">
                                    {{ $stagiaire->statut_libelle }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('stagiaires.show', $stagiaire) }}" class="text-primary me-2"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('stagiaires.edit', $stagiaire) }}" class="text-warning me-2"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('stagiaires.destroy', $stagiaire) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce stagiaire ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn p-0 text-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-users fa-2x text-secondary mb-2"></i><br>Aucun stagiaire trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stagiaires->hasPages())
            <div class="bg-light border-top py-2 px-3">
                {{ $stagiaires->links() }}
            </div>
        @endif
    </div>

    <!-- === STATISTIQUES === -->
    <div class="row g-3 mt-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary"><i class="fas fa-users"></i></div>
                <div class="stat-text">
                    <div class="stat-label">Total</div>
                    <div class="stat-value">{{ $stagiaires->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success"><i class="fas fa-check-circle"></i></div>
                <div class="stat-text">
                    <div class="stat-label">Actifs</div>
                    <div class="stat-value">{{ \App\Models\Stagiaire::where('statut', 'actif')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning"><i class="fas fa-pause-circle"></i></div>
                <div class="stat-text">
                    <div class="stat-label">Suspendus</div>
                    <div class="stat-value">{{ \App\Models\Stagiaire::where('statut', 'suspendu')->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-purple"><i class="fas fa-graduation-cap"></i></div>
                <div class="stat-text">
                    <div class="stat-label">Diplômés</div>
                    <div class="stat-value">{{ \App\Models\Stagiaire::where('statut', 'diplome')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/a2e0e9c6a4.js" crossorigin="anonymous"></script>
@endsection
