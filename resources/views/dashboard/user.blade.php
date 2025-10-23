@extends('layouts.app')

@section('title', 'Tableau de Bord Utilisateur')

@section('content')
    <h1>Tableau de Bord Utilisateur</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Stagiaires</h5>
                    <p class="card-text">{{ $data['total_stagiaires'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filières</h5>
                    <p class="card-text">{{ $data['total_filieres'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Salles</h5>
                    <p class="card-text">{{ $data['total_salles'] }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inscriptions Récentes</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Filière</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['recent_inscriptions'] as $stagiaire)
                                <tr>
                                    <td>{{ $stagiaire->nom }} {{ $stagiaire->prenom }}</td>
                                    <td>{{ $stagiaire->filiere->nom }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection