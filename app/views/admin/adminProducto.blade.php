@extends('layout')

@section('contenido')
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center text-blue">Gestión de Productos</h2>

            <div class="text-end mb-3">
                <a href="index.php?accion=agregarProducto" class="btn btn-success">+ Añadir producto</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Colección</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto['id'] }}</td>
                                <td>
                                    <img src="{{ $producto['imagen'] }}" alt="Imagen" width="60" class="img-thumbnail">
                                </td>
                                <td>{{ $producto['nombre'] }}</td>
                                <td>{{ $producto['categoria'] }}</td>
                                <td>{{ $producto['coleccion'] }}</td>
                                <td>{{ number_format($producto['precio'], 2) }} €</td>
                                <td>
                                    <a href="index.php?accion=editarProducto&id={{ $producto['id'] }}" class="btn btn-primary btn-sm me-1">
                                        ✏️ Editar
                                    </a>

                                    <button onclick="confirmarEliminacion({{ $producto['id'] }})" class="btn btn-danger btn-sm">
                                        🗑️ Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (isset($_GET['success']) && $_GET['success'] == '1')
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto añadido',
                        text: 'Se ha añadido el producto correctamente.',
                        confirmButtonColor: '#0085B4'
                    });
                </script>
            @endif

            @if (isset($_GET['deleted']) && $_GET['deleted'] == '1')
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto eliminado',
                        text: 'Se ha eliminado el producto correctamente.',
                        confirmButtonColor: '#0085B4'
                    });
                </script>
            @endif
        </div>
    </section>

    <script>
        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `index.php?accion=eliminarProducto&id=${id}`;
                }
            });
        }
    </script>
@endsection
