@extends('layout')

@section('contenido')
<?php require_once __DIR__ . '/../../../includes/funciones.php'; ?>
<?php $stock = obtenerStockReal($producto['id'], $conn); ?>

<section class="py-5">
    <div class="container">
        @if ($producto)
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" class="img-fluid mb-3 w-100">
                </div>

                <div class="col-md-6">
                    <h2 class="text-blue">{{ $producto['nombre'] }}</h2>
                    <p class="fw-bold text-blue fs-4">{{ number_format($producto['precio'], 2) }}€</p>
                    <p>{{ $producto['descripcion'] }}</p>

                    @if ($stock > 0)
                        <form id="formCarrito" action="index.php?accion=addCarrito" method="POST">
                            <input type="hidden" name="id_producto" value="{{ $producto['id'] }}">

                            <div class="mb-3 w-50">
                                <label for="talla" class="form-label">Talla:</label>
                                <select class="form-select" name="talla" required>
                                    <option value="">Selecciona talla</option>
                                    <option>S</option>
                                    <option>M</option>
                                    <option>L</option>
                                    <option>XL</option>
                                </select>
                            </div>

                            <div class="mb-3 w-50">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" class="form-control" name="cantidad" value="1" min="1" max="<?= $stock ?>" required>
                            </div>

                            <button type="button" id="btnConfirmarCarrito" class="btn btn-primary w-100">
                                <i class="fa fa-shopping-cart me-2"></i>Añadir al carrito
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning text-center">
                            Este producto está <strong>agotado</strong>.
                        </div>
                    @endif

                    {{-- Guía de tallas --}}
                    <details class="mt-4">
                        <summary class="mb-2">Guía de tallas</summary>
                        <table class="table table-bordered w-75">
                            <thead>
                                <tr><th>Talla</th><th>Pecho (cm)</th><th>Cintura (cm)</th></tr>
                            </thead>
                            <tbody>
                                <tr><td>S</td><td>88-94</td><td>76-82</td></tr>
                                <tr><td>M</td><td>95-100</td><td>83-88</td></tr>
                                <tr><td>L</td><td>101-106</td><td>89-94</td></tr>
                                <tr><td>XL</td><td>107-112</td><td>95-100</td></tr>
                            </tbody>
                        </table>
                    </details>
                </div>
            </div>
        @else
            <div class="alert alert-danger text-center">
                Producto no encontrado.
            </div>
        @endif
    </div>

    @if (isset($_GET['error']) && $_GET['error'] === 'stock')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Stock insuficiente',
                    text: 'No puedes añadir más cantidad de este producto al carrito.',
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
    @endif

    @if (isset($_GET['success']) && $_GET['success'] === '1')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Producto añadido',
                    text: 'Se ha añadido correctamente al carrito.',
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
    @endif

    <script>
        const usuarioLogueado = "{{ isset($_SESSION['usuario']) ? 'true' : 'false' }}";

        document.getElementById('btnConfirmarCarrito')?.addEventListener('click', function () {
            if (usuarioLogueado !== 'true') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Inicia sesión',
                    text: 'Debes iniciar sesión para añadir productos al carrito.',
                    confirmButtonColor: '#0085B4'
                }).then(() => {
                    window.location.href = 'index.php?accion=login';
                });
                return;
            }

            Swal.fire({
                title: '¿Agregar al carrito?',
                text: "¿Estás seguro de que quieres añadir este producto?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, añadir',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formCarrito').submit();
                }
            });
        });
    </script>
</section>
@endsection



