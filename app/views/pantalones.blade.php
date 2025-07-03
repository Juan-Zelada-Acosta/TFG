@extends('layout')

@section('contenido')
    <div class="container py-5">
        <h2 class="text-center text-blue mb-4"><i class="fa fa-tshirt me-2"></i>Pantalones</h2>

        <div class="d-flex justify-content-center gap-3 mb-4">
            <a href="index.php?accion=pantalones" class="btn {{ empty($coleccionActiva) ? 'btn-primary' : 'btn-outline-primary' }}">Todas</a>
            <a href="index.php?accion=pantalones&coleccion=nueva" class="btn {{ $coleccionActiva === 'nueva' ? 'btn-primary' : 'btn-outline-primary' }}">Nueva Colección</a>
            <a href="index.php?accion=pantalones&coleccion=rebajas" class="btn {{ $coleccionActiva === 'rebajas' ? 'btn-primary' : 'btn-outline-primary' }}">Rebajas</a>
            <a href="index.php?accion=pantalones&coleccion=estándar" class="btn {{ $coleccionActiva === 'estándar' ? 'btn-primary' : 'btn-outline-primary' }}">Colección Estándar</a>
        </div>

        @if (empty($productos))
            <div class="alert alert-info text-center">No hay pantalones disponibles en esta categoría.</div>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($productos as $producto)
                    <div class="col" data-aos="fade-in">
                        <div class="card h-100 border border-primary">
                            <div class="img-hover-container position-relative">
                                <a href="index.php?accion=producto&id={{ $producto['id'] }}">
                                    <img src="{{ $producto['imagen'] }}" class="card-img-top" alt="{{ $producto['nombre'] }}">
                                </a>
                                <span class="position-absolute top-0 start-0 badge rounded-end bg-primary text-white text-capitalize px-3 py-2">
                                    {{ $producto['coleccion'] }}
                                </span>
                                @if (isset($producto['stock']) && $producto['stock'] <= 0)
                                    <span class="position-absolute top-0 end-0 badge rounded-start bg-danger text-white px-3 py-2">
                                        Agotado
                                    </span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-blue">{{ $producto['nombre'] }}</h5>
                                <p class="fw-bold text-blue">{{ number_format($producto['precio'], 2) }}€</p>
                                <a href="index.php?accion=producto&id={{ $producto['id'] }}" class="btn btn-outline-primary mt-auto">Ver más</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
