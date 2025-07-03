@extends('layout')

@section('contenido')
    <section class="py-5">
        <div class="container">
            <h2 class="text-blue mb-4 text-center"><i class="fa fa-tshirt me-2"></i>Todos los productos</h2>

            {{-- Filtros por categoría --}}
            <div class="text-center mb-4">
                @php
                    $categorias = ['todas' => 'Todos', 'camisetas' => 'Camisetas', 'sudaderas' => 'Sudaderas', 'pantalones' => 'Pantalones', 'calcetines' => 'Calcetines'];
                    $catActiva = $_GET['categoria'] ?? 'todas';
                @endphp

                @foreach ($categorias as $clave => $nombre)
                    <a href="index.php?accion=productosFiltrar&categoria={{ $clave }}{{ isset($_GET['buscar']) ? '&buscar=' . urlencode($_GET['buscar']) : '' }}"
                       class="btn {{ $catActiva === $clave ? 'btn-primary' : 'btn-outline-primary' }} mx-1 mb-2">
                        {{ $nombre }}
                    </a>
                @endforeach
            </div>

            {{-- Buscador --}}
            <form method="GET" action="index.php" class="mb-5 text-center">
                <input type="hidden" name="accion" value="productosFiltrar">
                @if (isset($_GET['categoria']))
                    <input type="hidden" name="categoria" value="{{ $_GET['categoria'] }}">
                @endif
                <input type="search" name="buscar" placeholder="Buscar productos..." class="form-control w-50 d-inline"
                    value="{{ $busqueda ?? '' }}" aria-label="Buscar productos">
                <button type="submit" class="btn btn-primary ms-2">Buscar</button>
            </form>

            @if (!empty($productos))
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach ($productos as $producto)
                        <div class="col">
                            <div class="card h-100 border border-primary">
                                <a href="index.php?accion=verProducto&id={{ $producto['id'] }}">
                                    <img src="{{ $producto['imagen'] }}" class="card-img-top" alt="{{ $producto['nombre'] }}">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-blue">{{ $producto['nombre'] }}</h5>
                                    <p class="card-text fw-bold text-blue">{{ number_format($producto['precio'], 2) }}€</p>

                                    @if ($producto['stock'] > 0)
                                        <form action="index.php?accion=verProducto&id={{ $producto['id'] }}" method="POST" class="mt-auto">
                                            <input type="hidden" name="id_producto" value="{{ $producto['id'] }}">
                                            <input type="hidden" name="cantidad" value="1">
                                            <input type="hidden" name="talla" value="M">
                                            <button type="submit" class="btn btn-outline-primary w-100">
                                                <i class="fa fa-shopping-cart me-2"></i>Ver producto
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert alert-warning text-center mt-auto p-2">
                                            <i class="fa fa-exclamation-triangle me-1"></i>Agotado
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-danger text-center mt-4">
                    No hay productos disponibles en esta categoría.
                </div>
            @endif
        </div>
    </section>

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

    @if (isset($_GET['error']) && $_GET['error'] === 'stock')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Sin stock',
                    text: 'Este producto está agotado.',
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
    @endif
@endsection



