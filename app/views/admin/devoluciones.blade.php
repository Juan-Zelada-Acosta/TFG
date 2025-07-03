@extends('layout')

@section('contenido')
<div class="container py-5">
    <h2 class="text-center text-blue mb-4">Solicitudes de Devolución</h2>

    @if (empty($solicitudes))
        <div class="alert alert-info text-center">No hay devoluciones pendientes.</div>
    @else
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th># Pedido</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudes as $sol)
                    <tr>
                        <td>{{ $sol['id'] }}</td>
                        <td>{{ $sol['nombre'] }}</td>
                        <td>{{ $sol['fecha'] }}</td>
                        <td>{{ number_format($sol['total'], 2) }}€</td>
                        <td>
                            <a href="index.php?accion=aprobarDevolucion&id={{ $sol['id'] }}" class="btn btn-success btn-sm">Aceptar</a>
                            <a href="index.php?accion=rechazarDevolucion&id={{ $sol['id'] }}" class="btn btn-danger btn-sm">Rechazar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
