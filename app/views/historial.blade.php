@extends('layout')

@section('contenido')
<section class="py-5">
    <div class="container">
        <h2 class="mb-4 text-blue text-center"><i class="fa fa-list-alt me-2"></i>Historial de pedidos</h2>

        @if (isset($_SESSION['mensaje_devolucion']))
        <div class="alert alert-success text-center">
            {{ $_SESSION['mensaje_devolucion'] }}
        </div>
        @php unset($_SESSION['mensaje_devolucion']); @endphp
        @endif

        @if (isset($_SESSION['error_devolucion']))
        <div class="alert alert-danger text-center">
            {{ $_SESSION['error_devolucion'] }}
        </div>
        @php unset($_SESSION['error_devolucion']); @endphp
        @endif

        @if (empty($pedidos))
        <div class="alert alert-info text-center">No has realizado ningún pedido todavía.</div>
        @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido['id'] }}</td>
                        <td>{{ $pedido['fecha'] }}</td>
                        <td>{{ number_format($pedido['total'], 2) }}€</td>
                        <td>
                            @if ($pedido['estado'] === 'completado')
                            <span class="badge bg-success">Completado</span>
                            @elseif ($pedido['estado'] === 'pendiente')
                            <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif ($pedido['estado'] === 'devuelto')
                            <span class="badge bg-danger">Devuelto</span>
                            @elseif ($pedido['estado'] === 'pendiente_devolucion')
                            <span class="badge bg-info text-dark">Devolución solicitada</span>
                            @else
                            <span class="badge bg-secondary">{{ ucfirst($pedido['estado']) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="index.php?accion=detallePedido&id={{ $pedido['id'] }}"
                                class="btn btn-outline-primary btn-sm mb-2">Ver detalles</a>

                            @if ($pedido['estado'] === 'completado')
                            <form action="index.php?accion=devolverPedido" method="POST" class="form-devolver-pedido">
                                <input type="hidden" name="id_pedido" value="{{ $pedido['id'] }}">
                                <button type="submit" class="btn btn-danger btn-sm">Devolver pedido</button>
                            </form>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.form-devolver-pedido').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Deseas devolver este pedido?',
                    text: 'Esta acción marcará el pedido como pendiente de devolución y no habrá vuelta atrás.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, devolver',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection