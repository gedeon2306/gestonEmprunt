@extends('app/base')

@section('title', 'Gestion des Salariés')

@section('favicons')
    <link rel="shortcut icon" href="{{asset('static/images/entreprise.png')}}" type="image/png">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('static/css/styles.css')}}">
    <link rel="stylesheet" href="{{ asset('static/css/entreprise.css')}}">
    <link rel="stylesheet" href="{{ asset('static/css/salaries.css')}}">
@endsection

@section('content')
    <div class="container">
        <div class="header">
            <a href="{{ Route('entreprises.index') }}" class="btn btn-back">
                <i class="ri-arrow-left-line"></i> Retour
            </a>
            <h1>Gestion des Salariés - <span id="companyName">{{ $entreprise->nomEntreprise }}</span></h1>
            <button class="btn btn-add" onclick="openModal('addModal')">
                <i class="ri-user-add-line"></i> Ajouter un salarié
            </button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom complet</th>
                        <th>Téléphone</th>
                        <th>Genre</th>
                        <th>Seuil</th>
                        <th>Reste</th>
                        <th>Pin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($employers)>0)
                        @foreach ($employers as $employer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employer->nomComplet }}</td>
                                <td>{{ $employer->telephone }}</td>
                                <td>{{ $employer->genre }}</td>
                                <td>{{ $employer->seuil }} F</td>
                                <td>{{ $employer->reste }} F</td>
                                <td>{{ $employer->pin }}</td>
                                <td class="actions">
                                    <button class="btn-action btn-history" title="Historique des emprunts" onclick="window.location.href='{{ Route('employers.show', $employer->id)}}'">
                                        <i class="ri-bar-chart-horizontal-fill"></i>
                                    </button>
                                    <button class="btn-action btn-edit" title="Modifier" onclick="openEditModal('{{ Route('employers.update',$employer->id) }}', '{{ $employer->nomComplet }}', '{{ $employer->telephone }}', '{{ $employer->genre }}', '{{ $employer->seuil }}', '{{ $employer->pin }}')">
                                        <i class="ri-pencil-fill"></i>
                                    </button>
                                    <button class="btn-action btn-delete" title="Supprimer" data-url="{{ Route('employers.destroy', $employer->id) }}" data-message="cet employer">
                                        <i class="ri-delete-bin-2-line"></i>
                                    </button>
                                    <button class="btn-action btn-reinitialiser" title="Reinitialiser" data-url="{{ Route('employers.reset', $employer->id) }}" data-message="cet employer">
                                        <i class="ri-loop-right-line"></i>
                                    </button>
                                    <button class="btn-action btn-migrate" title="Migrer le compte" onclick="openMigrateModal('{{ Route('employers.migreEmployer', $employer->id) }}', '{{ $employer->nomComplet }}', '{{ $employer->entreprise_id }}')">
                                        <i class="ri-arrow-left-right-line"></i>
                                    </button>
                                    <label class="switch">
                                        <input 
                                            type="checkbox" 
                                            data-url="{{ Route('employers.disable', $employer->id) }}" 
                                            @if ($employer->counter != 2)
                                                data-message="Voulez-vous vraiment désactiver le compte de {{ $employer->nomComplet }} ?"
                                                data-response="Oui désactiver"
                                                checked
                                            @else
                                                data-message="Voulez-vous vraiment réactiver le compte de {{ $employer->nomComplet }} ?";
                                                data-response="Oui réactiver"
                                            @endif 
                                            onchange="disable(this)"
                                        >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="no-data">Aucun salarié enregistré.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajouter Salarié -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ajouter un salarié</h2>
                <button class="btn-close" onclick="closeModal('addModal')">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="addEmployeeForm" action="{{ Route('employers.store') }}" method="POST">
                @csrf
                <input type="hidden" name="entreprise_id" value="{{ $entreprise->id }}">
                <div class="form-group">
                    <label for="employeeName">Nom complet</label>
                    <input type="text" name="nomComplet" id="employeeName" required>
                </div>
                <div class="form-group">
                    <label for="employeePhone">Téléphone</label>
                    <input type="tel" name="telephone" id="employeePhone" required>
                </div>
                <div class="form-group">
                    <label for="employeeGender">Genre</label>
                    <select id="employeeGender" name="genre" required>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                        <option value="A">Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="employeeSeuil">Seuil d'emprunt (F)</label>
                    <input type="number" name="seuil" id="employeeSeuil" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('addModal')">Annuler</button>
                    <button type="submit" class="btn btn-submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier Salarié -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modifier le salarié</h2>
                <button class="btn-close" onclick="closeModal('editModal')">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="editEmployeeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="editEmployeeName">Nom complet</label>
                    <input type="text" name="nomComplet" id="editEmployeeName" required>
                </div>
                <div class="form-group">
                    <label for="editEmployeePhone">Téléphone</label>
                    <input type="tel" name="telephone" id="editEmployeePhone" required>
                </div>
                <div class="form-group">
                    <label for="editEmployeeGender">Genre</label>
                    <select id="editEmployeeGender" name="genre" required>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                        <option value="A">Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editEmployeeSeuil">Seuil d'emprunt (F)</label>
                    <input type="number" name="seuil" id="editEmployeeSeuil" required>
                </div>
                <div class="form-group">
                    <label for="editEmployeePin">Code PIN</label>
                    <input type="number" name="pin" id="editEmployeePin" required maxlength="4" pattern="[0-9]{4}">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('editModal')">Annuler</button>
                    <button type="submit" class="btn btn-submit">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Migration Salarié -->
    <div id="migrateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Migrer le salarié</h2>
                <button class="btn-close" onclick="closeModal('migrateModal')">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form id="migrateEmployeeForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Salarié à migrer</label>
                    <input type="text" id="migrateEmployeeName" readonly>
                </div>
                <div class="form-group">
                    <label for="targetCompany">Entreprise de destination</label>
                    <select id="targetCompany" name="entreprise_id" required>
                        @foreach ($allEntreprises as $ligne)
                            <option value="{{ $ligne->id }}">{{ $ligne->nomEntreprise }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeModal('migrateModal')">Annuler</button>
                    <button type="submit" class="btn btn-submit">Migrer</button>
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

        function openEditModal(url, name, phone, gender, seuil, pin) {
            document.getElementById('editEmployeeName').value = name;
            document.getElementById('editEmployeePhone').value = phone;
            document.getElementById('editEmployeeGender').value = gender;
            document.getElementById('editEmployeeSeuil').value = seuil;
            document.getElementById('editEmployeePin').value = pin;
            document.getElementById('editEmployeeForm').action = url;
            openModal('editModal');
        }

        function openMigrateModal(url, name, entrepriseId) {
            document.getElementById('migrateEmployeeName').value = name;
            document.getElementById('targetCompany').value = entrepriseId;
            document.getElementById('migrateEmployeeForm').action = url;
            openModal('migrateModal');
        }

        function disable(checkbox){
            const url = checkbox.getAttribute('data-url');
            const message = checkbox.getAttribute('data-message');
            const response = checkbox.getAttribute('data-response');
            const isChecked = checkbox.checked;
            
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: response,
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Soumettre le formulaire de suppression correspondant
                    const form = document.createElement('form');
                    form.action = url;
                    form.method = 'POST';

                    // CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Method spoofing for PATCH
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PATCH';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                } else {
                    // Si l'utilisateur annule, annulez l'état de la case à cocher
                    checkbox.checked = !isChecked;
                }
            });
        }

        // Fermer le modal si on clique en dehors
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Validation du PIN (4 chiffres uniquement)
        document.getElementById('editEmployeePin').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
        });
    </script>
@endsection