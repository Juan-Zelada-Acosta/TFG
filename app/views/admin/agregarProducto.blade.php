@extends('layout')

@section('contenido')
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center text-blue">Añadir Nuevo Producto</h2>

            <form action="index.php?accion=guardarProducto" method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" name="precio" id="precio" class="form-control" step="0.01" required>
                </div>

                <div class="col-md-6">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select name="categoria" id="categoria" class="form-select" required>
                        <option value="" disabled selected>Selecciona categoría</option>
                        <option value="camisetas">Camisetas</option>
                        <option value="sudaderas">Sudaderas</option>
                        <option value="pantalones">Pantalones</option>
                        <option value="calcetines">Calcetines</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="coleccion" class="form-label">Colección</label>
                    <select name="coleccion" id="coleccion" class="form-select" required>
                        <option value="" disabled selected>Selecciona colección</option>
                        <option value="nueva">Nueva</option>
                        <option value="rebajas">Rebajas</option>
                        <option value="estandar">Estándar</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required></textarea>
                </div>

                <div class="col-md-12">
                    <label for="imagen" class="form-label">Imagen del producto</label>
                    <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*" required>
                </div>

                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">Guardar producto</button>
                </div>
            </form>

            @if (isset($_GET['error']) && $_GET['error'] === 'campos')
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Campos obligatorios',
                        text: 'Debes rellenar todos los campos del formulario.',
                        confirmButtonColor: '#0085B4'
                    });
                </script>
            @endif

            @if (isset($_GET['error']) && $_GET['error'] === 'tipo')
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Tipo de imagen no permitido',
                        text: 'Solo se permiten imágenes JPEG, PNG o WEBP.',
                        confirmButtonColor: '#0085B4'
                    });
                </script>
            @endif

            @if (isset($_GET['error']) && $_GET['error'] === 'tamano')
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Imagen demasiado grande',
                        text: 'La imagen no puede superar los 2MB.',
                        confirmButtonColor: '#0085B4'
                    });
                </script>
            @endif

            @if (isset($_GET['success']) && $_GET['success'] === '1')
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto guardado',
                        text: 'El producto se ha añadido correctamente.',
                        confirmButtonColor: '#0085B4'
                    });
                </script>
            @endif
        </div>
    </section>
@endsection