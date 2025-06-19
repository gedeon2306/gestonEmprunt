@extends('app/base')

@section('title', 'Historique des Emprunts')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/entreprise.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/styles.css')}}">
    <link rel="stylesheet" href="{{ asset('static/css/departement.css')}}">
    <link rel="stylesheet" href="{{ asset('static/css/historique.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="header">
            <a href="{{ Route('departements.show', $employer->departement_id) }}" class="btn btn-back">
                <i class="ri-arrow-left-line"></i> Retour
            </a>
            <h1>Historique des Emprunts - <span id="employeeName">{{ $employer->nomComplet }}</span></h1>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <i class="ri-money-dollar-box-line"></i>
                <div class="card-content">
                    <h3>Seuil d'emprunt</h3>
                    <p>{{ $employer->seuil }} F</p>
                </div>
            </div>
            <div class="summary-card">
                <i class="ri-money-dollar-circle-line"></i>
                <div class="card-content">
                    <h3>Total Emprunté</h3>
                    <p>{{ $totalEmprunt }} F</p>
                </div>
            </div>
            <div class="summary-card">
                <i class="ri-bank-card-line"></i>
                <div class="card-content">
                    <h3>Reste à emprunté</h3>
                    <p>{{ $employer->reste }} F</p>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Motant</th>
                        <th>Statut</th>
                        <th>Date de remboursement</th>
                        <th>Reste à payer</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($emprunts)>0)
                        @foreach ($emprunts as $emprunt)
                            <tr>
                                <td>{{ $emprunt->created_at }}</td>
                                <td>{{ $emprunt->motant }} F</td>
                                <td><span class="status status-active">Terminé</span></td>
                                <td>Prochain salère</td>
                                <td>{{ $emprunt->motant }} F</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="no-data">Aucun emprunt effectué.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection