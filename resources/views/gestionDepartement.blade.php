@extends('app/base')

@section('title', 'Espace Entreprise')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/entreprise.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/styles.css')}}">
    <link rel="stylesheet" href="{{ asset('static/css/departement.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="header">
            <a href="{{ Route('entreprises.logout') }}" class="btn btn-back">
                <i class="ri-arrow-left-line"></i> Déconnexion
            </a>
            <h1>Gestion des departements - <span id="emtrepriseName">{{ session('entrepriseNom') }}</span></h1>
            <button class="btn btn-add" onclick="openModal('addModal')">
                <i class="ri-add-line"></i> Ajouter
            </button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom du département</th>
                        <th>Chef de département</th>
                        <th>Nombre de salariés</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($departements)>0)
                        @foreach ($departements as $departement)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $departement->nomDepartement }}</td>
                                <td>{{ $departement->chefDepartement }}</td>
                                <td>{{ $departement->nbSalarier }}</td>
                                <td class="actions">
                                    <button class="btn-action btn-enter" title="Entrer" onclick="window.location.href='{{ Route('departements.show', $departement->id) }}'">
                                        <i class="ri-login-box-line"></i>
                                    </button>
                                    <button class="btn-action btn-edit" title="Modifier" onclick="openEditModal('{{ Route('departements.update', $departement->id) }}', '{{ $departement->nomDepartement }}', '{{ $departement->chefDepartement }}')">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button class="btn-action btn-delete" title="Supprimer" data-url="{{ Route('departements.destroy', $departement->id) }}" data-message="ce département">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="no-data">Aucun departement enregistré.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajouter Département -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter un département</h2>
                <button class="btn-close" onclick="closeModal('addModal')">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="addDepartementForm" action="{{ route('departements.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="departementName">Nom du departement</label>
                    <input type="text" name="nomDepartement" id="departementName" required>
                </div>
                <div class="form-group">
                    <label for="departementChef">Chef du département</label>
                    <input type="text" name="chefDepartement" id="departementChef" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('addModal')">Annuler</button>
                    <button type="submit" class="btn btn-submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier Département -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modifier le département</h2>
                <button class="btn-close" onclick="closeModal('editModal')">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="editDepartementForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="editDepartementName">Nom du département</label>
                    <input type="text" name="nomDepartement" id="editDepartementName" required>
                </div>
                <div class="form-group">
                    <label for="editDepartementChef">Chef du département</label>
                    <input type="text" name="chefDepartement" id="editDepartementChef" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('editModal')">Annuler</button>
                    <button type="submit" class="btn btn-submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function openEditModal(url, name, address, phone, email) {
            document.getElementById('editDepartementName').value = name;
            document.getElementById('editDepartementChef').value = address;
            document.getElementById('editDepartementForm').action = url;
            openModal('editModal');
        }

        // Fermer le modal si on clique en dehors
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
@endsection