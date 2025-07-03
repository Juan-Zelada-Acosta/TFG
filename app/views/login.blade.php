@extends('layout')

@section('contenido')
    <div class="container py-5">
        <h2 class="text-center text-blue mb-4">Iniciar sesión</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if(isset($error))
                    <div class="alert alert-danger text-center">{{ $error }}</div>
                @endif

                <form method="POST" action="index.php?accion=procesarLogin">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="contrasena" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </div>
@endsection