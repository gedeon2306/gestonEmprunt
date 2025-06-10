@extends('app/base')

@section('title', 'Confirmation d\'emprunt')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/emprunt.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/loan-confirmation.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="confirmation-card">
            <div class="logo">
                <i class="ri-shield-check-line"></i>
            </div>
            <h1>Confirmation de votre emprunt</h1>
            @if ($employer->counter == 1)
                <p class="warning"><i class="ri-error-warning-fill"></i> Avertisemment sur le compte : Si vous entrez a noubeau un PIN incorrect le compte sera bloqué !</p>
            @endif

            @if ($employer->counter == 2)
                <p class="bloque"><i class="ri-error-warning-fill"></i> Votre compte à été bloqué contactez votre superviseur !</p>
            @endif
            
            <div class="loan-info">
                <div class="info-item">
                    <i class="ri-money-dollar-box-line"></i>
                    <div class="info-content">
                        <h3>Vous voulez emprunter</h3>
                        <p id="loanAmount">{{ $montant }} F</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="ri-money-dollar-circle-line"></i>
                    <div class="info-content">
                        <h3>Il vous restera</h3>
                        <p id="remainingAmount">{{ $employer->reste - $montant }} F</p>
                    </div>
                </div>
            </div>

            <form id="confirmationForm" class="confirmation-form" action="{{ Route('emprunts.store') }}" method="POST">
                @csrf
                <input type="number" name="employer_id" value="{{ $employer->id }}" hidden>
                <input type="number" name="motant" value="{{ $montant }}" hidden>
                <div class="input-group">
                    <i class="ri-lock-password-line"></i>
                    <input type="password" name="pin" placeholder="Entrez votre PIN" maxlength="4" required>
                </div>
                <button type="submit" class="btn btn-confirm">
                    <i class="ri-check-line"></i>
                    Confirmer l'emprunt
                </button>
                <button type="button" onclick="window.location.href='{{ Route('emprunts.redirection', ['id' => $employer->id]) }}'" class="btn btn-back">
                    <i class="ri-arrow-left-line"></i> 
                    Retour
                </button>
            </form>
        </div>
    </div>
@endsection