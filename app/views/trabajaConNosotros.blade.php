@extends('layout')

@section('contenido')
    <section class="py-5">
        <div class="container text-center">
            <h2 class="text-blue mb-4"><i class="fa fa-briefcase me-2"></i>Trabaja con Nosotros</h2>
            <p class="mb-4">En nuestra tienda buscamos personas apasionadas por la moda y con ganas de crecer. Si quieres
                unirte a nuestro equipo, completa el siguiente formulario:</p>

            <form method="POST" action="index.php?accion=enviarSolicitud" style="max-width: 600px;"
                class="mx-auto text-start">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                <div class="mb-3">
                    <label for="mensaje" class="form-label">Motivación o experiencia</label>
                    <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar solicitud</button>
            </form>
        </div>
    </section>

    @if(isset($_GET['exito']) && $_GET['exito'] === '1')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: '¡Solicitud enviada!',
                    text: 'Gracias por querer formar parte del equipo.',
                    icon: 'success',
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
    @endif
@endsection