@extends('layout')

@section('contenido')
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center text-blue">Mis pedidos</h2>

            @if (count($pedidos) > 0)
                <table class="table table-striped shadow text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            <tr>
                                <td>#{{ $pedido['id'] }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($pedido['fecha'])) }}</td>
                                <td>{{ number_format($pedido['total'], 2) }}€</td>
                                <td>
                                    <a href="index.php?accion=verDetalle&id={{ $pedido['id'] }}"
                                        class="btn btn-primary btn-sm">Ver</a>
                                    <a href="index.php?accion=descargarFactura&id={{ $pedido['id'] }}"
                                        class="btn btn-outline-secondary btn-sm">PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">Aún no has realizado ningún pedido.</div>
            @endif
        </div>
    </section>
@endsection