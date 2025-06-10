@extends('app/base')

@section('title', 'Espace Entreprise')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/entreprise.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/styles.css')}}">
    <link rel="stylesheet" href="{{ asset('static/css/entreprise.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="header">
            <a href="{{ Route('accueil') }}" class="btn btn-back">
                <i class="ri-arrow-left-line"></i> Retour
            </a>
            <h1>Gestion des Entreprises</h1>
            <button class="btn btn-add" onclick="openModal('addModal')">
                <i class="ri-add-line"></i> Ajouter
            </button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom de l'entreprise</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($entreprises)>0)
                        @foreach ($entreprises as $entreprise)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $entreprise->nomEntreprise }}</td>
                                <td>{{ $entreprise->adresse }}</td>
                                <td>{{ $entreprise->telephone }}</td>
                                <td>{{ $entreprise->email }}</td>
                                <td class="actions">
                                    <button class="btn-action btn-enter" title="Entrer" onclick="window.location.href='{{ Route('entreprises.show', $entreprise->id) }}'">
                                        <i class="ri-login-box-line"></i>
                                    </button>
                                    <button class="btn-action btn-edit" title="Modifier" onclick="openEditModal('{{ Route('entreprises.update', $entreprise->id) }}', '{{ $entreprise->nomEntreprise }}', '{{ $entreprise->adresse }}', '{{ $entreprise->telephone }}', '{{ $entreprise->email }}')">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button class="btn-action btn-delete" title="Supprimer" data-url="{{ Route('entreprises.destroy', $entreprise->id) }}" data-message="cette entreprise">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="no-data">Aucune entreprise enregistrée.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajouter Entreprise -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter une entreprise</h2>
                <button class="btn-close" onclick="closeModal('addModal')">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="addCompanyForm" action="{{ route('entreprises.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="companyName">Nom de l'entreprise</label>
                    <input type="text" name="nomEntreprise" id="companyName" required>
                </div>
                <div class="form-group">
                    <label for="companyAddress">Adresse</label>
                    <input type="text" name="adresse" id="companyAddress" required>
                </div>
                <div class="form-group">
                    <label for="companyPhone">Téléphone</label>
                    <input type="tel" name="telephone" id="companyPhone" required>
                </div>
                <div class="form-group">
                    <label for="companyEmail">Email</label>
                    <input type="email" name="email" id="companyEmail" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('addModal')">Annuler</button>
                    <button type="submit" class="btn btn-submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier Entreprise -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modifier l'entreprise</h2>
                <button class="btn-close" onclick="closeModal('editModal')">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="editCompanyForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="editCompanyName">Nom de l'entreprise</label>
                    <input type="text" name="nomEntreprise" id="editCompanyName" required>
                </div>
                <div class="form-group">
                    <label for="editCompanyAddress">Adresse</label>
                    <input type="text" name="adresse" id="editCompanyAddress" required>
                </div>
                <div class="form-group">
                    <label for="editCompanyPhone">Téléphone</label>
                    <input type="tel" name="telephone" id="editCompanyPhone" required>
                </div>
                <div class="form-group">
                    <label for="editCompanyEmail">Email</label>
                    <input type="email" name="email" id="editCompanyEmail" required>
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
            // document.getElementById('editCompanyId').value = id;
            document.getElementById('editCompanyName').value = name;
            document.getElementById('editCompanyAddress').value = address;
            document.getElementById('editCompanyPhone').value = phone;
            document.getElementById('editCompanyEmail').value = email;
            document.getElementById('editCompanyForm').action = url;
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