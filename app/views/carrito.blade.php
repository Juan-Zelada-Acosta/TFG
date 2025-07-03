@extends('layout')

@section('contenido')
<section class="py-5">
    <div class="container">
        <h2 class="text-center text-blue mb-4">Tu Carrito de Compras</h2>

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

        @if (isset($_GET['error']) && $_GET['error'] === 'producto_no_encontrado')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Producto no disponible',
                        text: 'Uno de los productos del carrito ya no existe en la tienda.',
                        confirmButtonColor: '#0085B4'
                    });
                });
            </script>
        @endif

        <div id="tablaCarrito">
            @if (empty($productos))
                <div class="alert alert-info text-center">
                    Tu carrito está vacío.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Talla</th>
                                <th>Cantidad</th>
                                <th>Precio unitario</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($productos as $item)
                                @php
                                    $subtotal = $item['precio'] * $item['cantidad'];
                                    $total += $subtotal;
                                @endphp
                                <tr class="text-center">
                                    <td style="width: 100px;">
                                        <img src="{{ $item['imagen'] }}" class="img-fluid rounded" alt="{{ $item['nombre'] }}">
                                    </td>
                                    <td>{{ $item['nombre'] }}</td>
                                    <td>{{ $item['talla'] }}</td>
                                    <td>{{ $item['cantidad'] }}</td>
                                    <td>{{ number_format($item['precio'], 2) }}€</td>
                                    <td>{{ number_format($subtotal, 2) }}€</td>
                                    <td>
                                        <form class="form-eliminar-carrito d-inline" data-id="{{ $item['id'] }}">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total:</td>
                                <td colspan="2" class="fw-bold text-blue">{{ number_format($total, 2) }}€</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <a href="index.php?accion=finalizarCompra" class="btn btn-primary">
                        <i class="fa fa-credit-card me-1"></i> Finalizar compra
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const formularios = document.querySelectorAll(".form-eliminar-carrito");

    formularios.forEach(form => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const idLinea = this.dataset.id;
            const fila = this.closest("tr");

            Swal.fire({
                title: '¿Eliminar producto?',
                text: "¿Estás seguro de quitar este producto del carrito?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('index.php?accion=eliminarCarrito', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Cache-Control': 'no-cache'
                        },
                        cache: 'no-store',
                        body: new URLSearchParams({
                            id_carrito: idLinea
                        })
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'ok') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: 'Producto eliminado del carrito.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            fila.style.transition = "opacity 0.5s ease";
                            fila.style.opacity = "0";

                            setTimeout(() => {
                                fila.remove();

                                const tbody = document.querySelector("tbody");
                                if (tbody.children.length === 1) {
                                    document.getElementById("tablaCarrito").innerHTML = `
                                        <div class="alert alert-info text-center">
                                            Tu carrito está vacío.
                                        </div>
                                    `;
                                }

                            }, 500);
                        } else {
                            Swal.fire('Error', 'No se pudo eliminar el producto.', 'error');
                            console.error("Respuesta inesperada:", data);
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Error de red al intentar eliminar.', 'error');
                        console.error("Error de red:", err);
                    });
                }
            });
        });
    });
});
</script>
@endsection
