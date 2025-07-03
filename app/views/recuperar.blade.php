@extends('layout')

@section('titulo', 'Recuperar Contraseña')

@section('contenido')
<div class="d-flex justify-content-center pt-5 pb-3">
    <div class="col-md-6 bg-white p-4 shadow rounded">
        <h2 class="mb-4 text-center text-blue">Recupera tu contraseña</h2>
        <form action="index.php?accion=enviarRecuperacion" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <button type="submit" class="btn w-100">Enviar instrucciones</button>
        </form>
        <div class="mt-3 text-center">
            <a href="index.php?accion=login" class="d-block text-muted small">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </div>
</div>

@if(isset($_SESSION['mensaje_recuperacion']))
@php unset($_SESSION['mensaje_recuperacion']); @endphp
<script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            title: '¡Correo enviado!',
            text: '📩 Te hemos enviado un correo con las instrucciones.',
            icon: 'success',
            confirmButtonColor: '#0085B4'
        });
    });
</script>
@endif
@endsection
