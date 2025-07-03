@extends('layout')

@section('contenido')
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center text-blue">Gestión de Productos</h2>

            <div class="mb-3 text-end">
                <a href="index.php?accion=agregarProducto" class="btn btn-success">Añadir nuevo producto</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Categoría</th>
                            <th>Colección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto['id'] }}</td>
                                <td>
                                    <img src="{{ $producto['imagen'] }}" alt="img"
                                         style="width: 70px; height: 70px; object-fit: cover;"
                                         class="rounded">
                                </td>
                                <td>{{ $producto['nombre'] }}</td>
                                <td>{{ number_format($producto['precio'], 2) }}€</td>
                                <td>{{ ucfirst($producto['categoria']) }}</td>
                                <td>{{ ucfirst($producto['coleccion']) }}</td>
                                <td>
                                    <a href="index.php?accion=editarProducto&id={{ $producto['id'] }}"
                                       class="btn btn-primary btn-sm me-1">
                                        Editar
                                    </a>
                                    <a href="#"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirmarEliminacion(event, {{ $producto['id'] }})">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        @if (count($productos) === 0)
                            <tr>
                                <td colspan="7">No hay productos disponibles.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarEliminacion(e, id) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el producto permanentemente.',
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
            return false;
        }
    </script>
@endsection
