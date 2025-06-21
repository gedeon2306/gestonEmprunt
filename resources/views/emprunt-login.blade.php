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
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const tel = this.querySelectorAll('input[type="tel"]')[0].value;

            if (!tel) {
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
                    title: "Entrez votre numéro de téléphone !"
                });
                return
            }

            // Animation du bouton
            const btn = this.querySelector('.btn-verify');
            btn.innerHTML = '<i class="ri-loader-4-line"></i> Vérification...';
            btn.disabled = true;

            setTimeout(() => {
                e.target.submit();
            }, 1500);
        });
    </script>
@endsection