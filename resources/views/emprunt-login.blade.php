@extends('app/base')

@section('title', 'Espace Salarié - Connexion')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/emprunt.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/employee-login.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="login-card">
            <div class="logo">
                <i class="ri-user-line"></i>
            </div>
            <h1>Bienvenue sur votre espace emprunt</h1>
            <p class="welcome-text">Veuillez entrer votre numéro de téléphone pour accéder à votre espace personnel</p>
            
            <form id="loginForm" class="login-form" action="{{ Route('emprunts.verif') }}" method="POST">
                @csrf
                <div class="input-group">
                    <i class="ri-phone-line"></i>
                    <input type="tel" id="phoneNumber" name="telephone" placeholder="Numéro de téléphone" required>
                </div>
                <button type="submit" class="btn btn-verify">
                    <i class="ri-check-line"></i>
                    Vérifier
                </button>
                <button type="button" onclick="window.location.href='{{ Route('accueil') }}'" class="btn btn-back">
                    <i class="ri-arrow-left-line"></i> 
                    Retour
                </button>
            </form>
        </div>
    </div>
@endsection