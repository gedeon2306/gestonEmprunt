<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système d'Emprunt</title>
    <link rel="shortcut icon" href="{{asset('static/images/gestionEmprunt.png')}}" type="image/png">
    <link href="{{ asset('static/remixicon/remixicon.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('static/css/styles.css') }}">
</head>
<body>
    <h1 class="welcome-text">Bienvenue, vous êtes :</h1>
    
    <div class="cards-container">
        <a href="{{ Route('entreprises.authentification') }}" class="card">
            <i class="ri-building-2-line"></i>
            <h2>Entreprise</h2>
            <p>Accédez à votre espace entreprise pour gérer les demandes d'emprunt de vos salariés</p>
        </a>

        <a href="{{ Route('emprunts.index') }}" class="card">
            <i class="ri-user-line"></i>
            <h2>Salarié</h2>
            <p>Accédez à votre espace personnel pour faire une demande d'emprunt</p>
        </a>
    </div>
</body>
</html> 