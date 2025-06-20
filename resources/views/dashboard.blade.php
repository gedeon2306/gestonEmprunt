@extends('app/base')

@section('title', 'Espace Entreprise - Dashboard')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/entreprise.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/styles.css')}}">
    <link rel="stylesheet" href="{{ asset('static/css/departement.css')}}">
    <link rel="stylesheet" href="{{ asset('static/css/dashboard.css')}}">
@endsection

@section('content')
    <div class="container dashboard-container">
        <div class="header">
            <a href="{{ Route('departements.index') }}" class="btn btn-back">
                <i class="ri-arrow-left-line"></i> Retour
            </a>
            <h1>Tableau de bord de l'entreprise</h1>
        </div>

        <!-- Formulaire d'informations sur l'entreprise -->
        <form class="dashboard-form">
            <h2><i class="ri-building-2-line"></i> Informations sur l'entreprise</h2>
            <div class="form-group">
                <div class="input-group">
                    <i class="ri-building-line"></i>
                    <input type="text" id="companyName" name="nomEntreprise" value="{{ $entreprise->nomEntreprise }}" placeholder="Nom de l'entreprise" required>
                </div>
                <div class="input-group">
                    <i class="ri-mail-line"></i>
                    <input type="email" id="companyEmail" name="email" value="{{ $entreprise->email }}" placeholder="Email de l'entreprise" required>
                </div>
            </div>
            <!-- Changement de mot de passe -->
            <div class="form-group">
                <div class="input-group">
                    <i class="ri-lock-password-line"></i>
                    <input type="password" id="oldPassword" placeholder="Ancien mot de passe" autocomplete="current-password">
                </div>
            </div>
            <div class="password-row">
                <div class="form-group">
                    <div class="input-group">
                        <i class="ri-lock-line"></i>
                        <input type="password" id="newPassword" placeholder="Nouveau mot de passe" autocomplete="new-password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <i class="ri-lock-line"></i>
                        <input type="password" id="confirmPassword" placeholder="Confirmer le nouveau mot de passe" autocomplete="new-password">
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-submit">
                    <i class="ri-save-3-line"></i> Enregistrer
                </button>
            </div>
        </form>

        <!-- Statistiques principales -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <i class="ri-group-line"></i>
                <div class="stat-content">
                    <h3>Départements</h3>
                    <p id="departementCount">{{ count($departements) }}</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="ri-user-3-line"></i>
                <div class="stat-content">
                    <h3>Salariés</h3>
                    <p id="employeeCount">{{ count($employers) }}</p>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="dashboard-graphs">
            <div class="graph-card">
                <h3><i class="ri-bar-chart-2-line"></i> Dépt. avec le plus d'emprunts (montant)</h3>
                <canvas id="loanChart"></canvas>
            </div>
            <div class="graph-card">
                <h3><i class="ri-pie-chart-2-line"></i> Dépt. avec le plus de salariés</h3>
                <canvas id="employeeChart"></canvas>
            </div>
        </div>

        <!-- Zone danger -->
        <div class="danger-zone">
            <h2><i class="ri-error-warning-line"></i> Danger</h2>
            <p>La suppression du compte est irréversible. Toutes les données de l'entreprise seront perdues.</p>
            <button class="btn btn-danger btn-delete" data-url="{{ Route('entreprises.destroy', $entreprise->id) }}" data-message="cette entreprise">
                <i class="ri-delete-bin-6-line"></i> Supprimer le compte
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Animation d'apparition des éléments
        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.stat-card, .graph-card, .dashboard-form, .danger-zone').forEach(el => {
                el.style.opacity = 0;
                setTimeout(() => {
                    el.style.transition = 'opacity 0.7s';
                    el.style.opacity = 1;
                }, 200);
            });
        });

        // Mock data pour les graphiques
        const loanChartData = {
            labels: ['Production', 'RH', 'Finance', 'IT', 'Ventes'],
            datasets: [{
                label: 'Montant total emprunté (F)',
                data: [12000, 8000, 15000, 5000, 7000],
                backgroundColor: [
                    '#3498db', '#2ecc71', '#f1c40f', '#e67e22', '#9b59b6'
                ],
                borderRadius: 8
            }]
        };

        const employeeChartData = {
            labels: ['Production', 'RH', 'Finance', 'IT', 'Ventes'],
            datasets: [{
                label: 'Nombre de salariés',
                data: [40, 20, 25, 15, 20],
                backgroundColor: [
                    '#2ecc71', '#3498db', '#f1c40f', '#e67e22', '#9b59b6'
                ],
                borderRadius: 8
            }]
        };

        // Initialisation des graphiques
        window.addEventListener('DOMContentLoaded', () => {
            const loanCtx = document.getElementById('loanChart').getContext('2d');
            new Chart(loanCtx, {
                type: 'bar',
                data: loanChartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: false }
                    },
                    animation: {
                        duration: 1200,
                        easing: 'easeOutBounce'
                    }
                }
            });

            const employeeCtx = document.getElementById('employeeChart').getContext('2d');
            new Chart(employeeCtx, {
                type: 'doughnut',
                data: employeeChartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: false }
                    },
                    animation: {
                        duration: 1200,
                        easing: 'easeOutCirc'
                    }
                }
            });
        });

        // Gestion du formulaire (exemple, à adapter selon backend)
        document.querySelector('.dashboard-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // Animation de validation
            this.classList.add('validated');
            setTimeout(() => {
                this.classList.remove('validated');
                alert('Informations enregistrées !');
            }, 800);
        });

    </script>
@endsection