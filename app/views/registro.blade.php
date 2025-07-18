@extends('layout')

@section('titulo', 'Registro')

@section('contenido')
<div class="d-flex justify-content-center pt-5 pb-3">
    <div class="col-md-6 bg-white p-4 shadow rounded">
        <h2 class="mb-4 text-center text-blue">Crea tu cuenta</h2>

        @if(isset($_GET['error']) && $_GET['error'] === 'email')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Correo ya registrado',
                    text: 'Ya existe un usuario con ese correo electrónico. Prueba con otro.',
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
        @endif

        <form method="POST" action="index.php?accion=registrarUsuario">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn w-100">Registrarse</button>
        </form>

        <div class="mt-3 text-center">
            <a href="index.php?accion=login" class="d-block text-muted small">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</div>
<script>
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const email = document.getElementById('email').value.trim();
            const fecha = document.getElementById('fecha').value;
            const password = document.getElementById('password').value;

            const hoy = new Date();
            const nacimiento = new Date(fecha);
            const edad = hoy.getFullYear() - nacimiento.getFullYear();
            const edadAjustada = hoy.getMonth() > nacimiento.getMonth() ||
                (hoy.getMonth() === nacimiento.getMonth() && hoy.getDate() >= nacimiento.getDate()) ?
                edad : edad - 1;

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (nombre.length < 3) {
                e.preventDefault();
                return Swal.fire({
                    icon: 'error',
                    title: 'Nombre inválido',
                    text: 'El nombre debe tener al menos 3 caracteres.',
                    confirmButtonColor: '#0085B4'
                });
            }

            if (!emailRegex.test(email)) {
                e.preventDefault();
                return Swal.fire({
                    icon: 'error',
                    title: 'Correo inválido',
                    text: 'Introduce un correo electrónico válido.',
                    confirmButtonColor: '#0085B4'
                });
            }

            if (!fecha || isNaN(nacimiento) || edadAjustada < 14) {
                e.preventDefault();
                return Swal.fire({
                    icon: 'error',
                    title: 'Fecha no válida',
                    text: 'Debes tener al menos 14 años para registrarte.',
                    confirmButtonColor: '#0085B4'
                });
            }

            if (password.length < 6) {
                e.preventDefault();
                return Swal.fire({
                    icon: 'error',
                    title: 'Contraseña débil',
                    text: 'La contraseña debe tener al menos 6 caracteres.',
                    confirmButtonColor: '#0085B4'
                });
            }
        });
    });
</script>

@endsection