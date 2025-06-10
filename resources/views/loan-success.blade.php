@extends('app/base')

@section('title', 'Emprunt Réussi')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/emprunt.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/loan-success.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="success-card">
            <div class="logo success">
                <i class="ri-check-line"></i>
            </div>
            <h1>Emprunt Réussi !</h1>
            
            <div class="success-message">
                <p>Votre demande d'emprunt a été validée avec succès.</p>
                <p class="amount">Montant emprunté : <span id="loanAmount">{{ $montant }} F</span></p>
            </div>

            <button onclick="window.location.href='{{ Route('emprunts.redirection', ['id' => $employer->id]) }}'" class="btn-home">
                <i class="ri-home-line"></i>
                Retour à l'accueil
            </button>
        </div>
    </div>
@endsection