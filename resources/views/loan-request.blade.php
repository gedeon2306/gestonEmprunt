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
                <input type="number" name="employer_id" value="{{ $employer->id }}" hidden required>
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
    <script>
        document.getElementById('loanForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const number = this.querySelectorAll('input[type="number"]')[1].value;

            if (!number) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "error",
                    title: "Entrez un montant !"
                });
                return
            }

            // Animation du bouton
            const btn = this.querySelector('.btn-request');
            btn.innerHTML = '<i class="ri-loader-4-line"></i> En cours...';
            btn.disabled = true;

            setTimeout(() => {
                e.target.submit();
            }, 1500);
        });
    </script>
@endsection