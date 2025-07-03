@extends('layout')

@section('contenido')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-blue">Detalles del Pedido #{{ $pedido['id'] }}</h2>
            <span class="badge fs-6 
                @if ($pedido['estado'] === 'completado') bg-success
                @elseif ($pedido['estado'] === 'pendiente') bg-warning text-dark
                @elseif ($pedido['estado'] === 'devuelto') bg-danger
                @elseif ($pedido['estado'] === 'pendiente_devolucion') bg-info text-dark
                @else bg-secondary @endif">
                {{ ucfirst($pedido['estado']) }}
            </span>
        </div>

        <p class="text-muted mb-4 text-end">
            <i class="fa fa-calendar me-1"></i> Pedido realizado el: {{ date('d/m/Y', strtotime($pedido['fecha'])) }}
        </p>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title text-blue"><i class="fa fa-truck me-2"></i>Dirección de envío</h5>
                <p class="card-text">
                    {{ $pedido['nombre'] }}<br>
                    {{ $pedido['direccion'] }}
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Talla</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($pedido['productos']))
                        @foreach ($pedido['productos'] as $producto)
                            <tr>
                                <td>
                                    <img src="{{ $producto['imagen_producto'] ?? $producto['imagen'] }}" alt="{{ $producto['nombre_producto'] ?? $producto['nombre'] }}" class="img-thumbnail me-2" style="width: 60px; height: auto;">
                                    {{ $producto['nombre_producto'] ?? $producto['nombre'] }}
                                </td>
                                <td>{{ $producto['talla'] }}</td>
                                <td>{{ number_format($producto['precio_unitario'] ?? $producto['precio'], 2) }} €</td>
                                <td>{{ $producto['cantidad'] }}</td>
                                <td>{{ number_format(($producto['precio_unitario'] ?? $producto['precio']) * $producto['cantidad'], 2) }} €</td>
                            </tr>
                        @endforeach
                        <tr class="fw-bold bg-light">
                            <td colspan="4" class="text-end">Total:</td>
                            <td class="text-blue">{{ number_format($pedido['total'], 2) }} €</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No hay productos en este pedido.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="text-end mt-4">
            <a href="index.php?accion=descargarFactura&id={{ $pedido['id'] }}" class="btn btn-outline-primary">
                <i class="fa fa-file-pdf me-2"></i>Descargar factura en PDF
            </a>
        </div>
    </div>
</section>
@endsection
