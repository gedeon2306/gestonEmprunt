<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @yield('favicons')
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="{{ asset('static/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('static/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    @yield('styles')
</head>
<body>
    @yield('content')

    @if (session('success'))
    <script>
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
            icon: "success",
            title: "{{session('success')}}"
        });
    </script>
    @endif

    @if (session('error'))
    <script>
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
            title: "{{session('error')}}"
        });
    </script>
    @endif

    @if (session('warning'))
    <script>
        Swal.fire({
            title: "Erreur !",
            text: "{{session('warning')}}",
            icon: "error"
        });
    </script>
    @endif

    @if (session('info'))
    <script>
        Swal.fire({
            title: "Succès !",
            text: "{{session('info')}}",
            icon: "success"
        });
    </script>
    @endif

    <script>
        // Confirmation de suppression
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                const message = this.getAttribute('data-message');
                
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Voulez-vous vraiment supprimer "+ message +" ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
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

                        // Method spoofing for DELETE
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        document.querySelectorAll('.btn-reinitialiser').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                const message = this.getAttribute('data-message');
                
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Voulez-vous vraiment reinitialiser le reste à emprunter de "+ message +" ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
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
                    }
                });
            });
        });
        
        document.querySelectorAll('.btn-reinitialiser').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                const message = this.getAttribute('data-message');
                
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Voulez-vous vraiment reinitialiser le reste à emprunter de "+ message +" ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
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
                    }
                });
            });
        });
    </script>
</body>
</html> 