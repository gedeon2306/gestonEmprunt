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
        const companyName = this.querySelector('input[type="email"]').value;
        const password = this.querySelector('input[type="password"]').value;

        // Animation du bouton
        const btn = this.querySelector('.btn-submit');
        btn.innerHTML = '<i class="ri-loader-4-line"></i> Connexion...';
        btn.disabled = true;

        // Simuler une requête de connexion
        setTimeout(() => {
            console.log('Tentative de connexion:', { companyName, password });
            window.location.href = 'departement.html';
        }, 1500);
    });

    // Gestion du formulaire d'inscription
    document.getElementById('register-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const companyName = this.querySelector('input[type="text"]').value;
        const email = this.querySelector('input[type="email"]').value;
        const password = this.querySelectorAll('input[type="password"]')[0].value;
        const confirmPassword = this.querySelectorAll('input[type="password"]')[1].value;

        if (password !== confirmPassword) {
            showError('Les mots de passe ne correspondent pas !');
            return;
        }

        // Animation du bouton
        const btn = this.querySelector('.btn-submit');
        btn.innerHTML = '<i class="ri-loader-4-line"></i> Inscription...';
        btn.disabled = true;

        // Simuler une requête d'inscription
        setTimeout(() => {
            console.log('Tentative d\'inscription:', { companyName, email, password });
            window.location.href = 'departement.html';
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
