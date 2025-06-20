@extends('app/base')

@section('title', 'Demande d\'emprunt')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/emprunt.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/loan-request.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="loan-card">
            <div class="logo">
                <i class="ri-user-line"></i>
            </div>
            <h1>Bienvenue <span id="userName">{{ $employer->nomComplet }}</span></h1>
            @if ($employer->counter == 1)
                <p class="warning"><i class="ri-error-warning-fill"></i> Avertisemment sur le compte : Si vous entrez a nouveau un PIN incorrect le compte sera bloqué !</p>
            @endif

            @if ($employer->counter == 2)
                <p class="bloque"><i class="ri-error-warning-fill"></i> Votre compte à été bloqué contactez votre superviseur !</p>
            @endif
            
            <div class="loan-info">
                <div class="info-item">
                    <i class="ri-money-dollar-box-line"></i>
                    <div class="info-content">
                        <h3>Votre seuil d'emprunt</h3>
                        <p>{{ $employer->seuil }} F</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="ri-money-dollar-circle-line"></i>
                    <div class="info-content">
                        <h3>Vous pouvez emprunter jusqu'à</h3>
                        <p>{{ $employer->reste }} F</p>
                    </div>
                </div>
            </div>

            <form id="loanForm" class="loan-form" action="{{ Route('emprunts.confirm')}}" method="POST">
                @csrf
                <input type="number" name="employer_id" value="{{ $employer->id }}" hidden>
                <div class="input-group">
                    <i class="ri-money-euro-circle-line"></i>
                    <input type="number" id="loanAmount" name="motant" placeholder="Montant souhaité" required>
                </div>
                <button type="submit" class="btn btn-request">
                    <i class="ri-send-plane-fill"></i>
                    Demander l'emprunt
                </button>
                <button type="button" onclick="window.location.href='{{ Route('emprunts.logout') }}'" class="btn btn-back">
                    <i class="ri-arrow-left-line"></i> 
                    Quiter
                </button>
            </form>
        </div>
    </div>
@endsection