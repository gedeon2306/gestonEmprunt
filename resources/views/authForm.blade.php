@extends('app/base')

@section('title', 'Espace entreprise - Authentification')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/entreprise.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/auth.css')}}">
@endsection

@section('content')
    <div class="auth-container">
        <div class="auth-box">
            <div class="tabs">
                <button class="tab-btn active" data-tab="login">
                    <i class="ri-login-box-line"></i>
                    Connexion
                </button>
                <button class="tab-btn" data-tab="register">
                    <i class="ri-user-add-line"></i>
                    Inscription
                </button>
            </div>

            <div class="tab-content">
                <!-- Formulaire de connexion -->
                <form id="login-form" method="POST" action="{{ Route('entreprises.login') }}" class="form active">
                    @csrf
                    <div class="form-header">
                        <i class="ri-building-2-line"></i>
                        <h2>Bienvenue</h2>
                        <p>Connectez-vous à votre espace entreprise</p>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group">
                            <i class="ri-building-line"></i>
                            <input type="email" name="email" placeholder="Adresse email de l'entreprise" required>
                        </div>
                        <div class="input-group">
                            <i class="ri-lock-line"></i>
                            <input type="password" name="password" placeholder="Mot de passe" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-back" onclick="window.location.href='{{ Route('accueil') }}'"> 
                            <i class="ri-arrow-left-line"></i>
                            <span>Retour</span>
                        </button>
                        <button type="submit" class="btn btn-submit">
                            <span>Se connecter</span>
                            <i class="ri-arrow-right-line"></i>
                        </button>
                    </div>
                </form>

                <!-- Formulaire d'inscription -->
                <form id="register-form" method="POST" action="{{ Route('entreprises.store') }}" class="form">
                    @csrf
                    <div class="form-header">
                        <i class="ri-user-add-line"></i>
                        <h2>Créer un compte</h2>
                        <p>Rejoignez notre plateforme</p>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group">
                            <i class="ri-building-line"></i>
                            <input type="text" name="nomEntreprise" placeholder="Nom de l'entreprise" required>
                        </div>
                        <div class="input-group">
                            <i class="ri-mail-line"></i>
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-group">
                            <i class="ri-lock-line"></i>
                            <input type="password" name="password" placeholder="Mot de passe" required>
                        </div>
                        <div class="input-group">
                            <i class="ri-lock-line"></i>
                            <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-back" onclick="window.location.href='{{ Route('accueil') }}'"> 
                            <i class="ri-arrow-left-line"></i>
                            <span>Retour</span>
                        </button>
                        <button type="submit" class="btn btn-submit">
                            <span>S'inscrire</span>
                            <i class="ri-arrow-right-line"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sélectionner les éléments
            const tabBtns = document.querySelectorAll('.tab-btn');
            const forms = document.querySelectorAll('.form');

            // Gestion des onglets
            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Retirer la classe active de tous les boutons
                    tabBtns.forEach(b => b.classList.remove('active'));
                    // Ajouter la classe active au bouton cliqué
                    btn.classList.add('active');

                    // Afficher le formulaire correspondant
                    const formId = btn.getAttribute('data-tab') + '-form';
                    forms.forEach(form => {
                        if (form.id === formId) {
                            form.classList.add('active');
                        } else {
                            form.classList.remove('active');
                        }
                    });
                });
            });

            // Gestion du formulaire de connexion
            document.getElementById('login-form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Animation du bouton
                const btn = this.querySelector('.btn-submit');
                btn.innerHTML = '<i class="ri-loader-4-line"></i> Connexion...';
                btn.disabled = true;

                setTimeout(() => {
                    e.target.submit();
                }, 1500);
            });

            // Gestion du formulaire d'inscription
            document.getElementById('register-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const password = this.querySelectorAll('input[type="password"]')[0].value;
                const confirmPassword = this.querySelectorAll('input[type="password"]')[1].value;

                if (password !== confirmPassword) {
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
                        title: "Les deux mot de passe ne correspondent pas !"
                    });
                    return
                }

                // Animation du bouton
                const btn = this.querySelector('.btn-submit');
                btn.innerHTML = '<i class="ri-loader-4-line"></i> Inscription...';
                btn.disabled = true;

                setTimeout(() => {
                    e.target.submit();
                }, 1500);
            });

            // Fonction pour afficher les erreurs
            function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = `
                    <i class="ri-error-warning-line"></i>
                    <span>${message}</span>
                `;
                document.querySelector('.auth-box').insertBefore(errorDiv, document.querySelector('.tabs'));
                
                setTimeout(() => {
                    errorDiv.remove();
                }, 3000);
            }
        });
    </script>
@endsection