@extends('layout')

@section('contenido')

    {{-- SweetAlert si no hay administrador --}}
    @if (isset($mostrarAdvertenciaAdmin) && $mostrarAdvertenciaAdmin)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Administrador no encontrado',
                    text: 'No hay ningún administrador registrado. Por favor, crea uno desde el registro.',
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
    @endif

    {{-- Carrusel principal --}}
    <div id="carouselExampleIndicators" class="carousel slide carrusel-personalizado" data-bs-ride="carousel"
        data-bs-interval="3500">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/assets/img/carruselCamisetas.png" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item">
                <img src="/assets/img/carruselSudaderas.png" class="d-block w-100" alt="Banner 2">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    {{-- Sección Recién llegados con fondo --}}
    <section class="section-recién-llegados">
        <div class="container">
            <h2 class="mt-5 mb-5 text-center text-blue"><i class="fa fa-bolt me-2"></i>Imprescindibles</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($productosRecientes as $producto)
                    <div class="col" data-aos="fade-in">
                        <div class="card h-100 border border-primary">
                            <div class="img-hover-container position-relative">
                                <a href="index.php?accion=producto&id={{ $producto['id'] }}">
                                    <img src="{{ $producto['imagen'] }}" class="card-img-top" alt="{{ $producto['nombre'] }}">
                                </a>
                                <span
                                    class="position-absolute top-0 start-0 badge rounded-end bg-primary text-white text-capitalize px-3 py-2">
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
                                <a href="index.php?accion=producto&id={{ $producto['id'] }}"
                                    class="btn btn-outline-primary mt-auto">Ver más</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- SweetAlert si hubo acceso denegado --}}
    @if (isset($_SESSION['acceso_denegado']))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso denegado',
                    text: "{!! addslashes($_SESSION['acceso_denegado']) !!}",
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
        @php unset($_SESSION['acceso_denegado']); @endphp
    @endif

    {{-- SweetAlert si el pedido se procesó correctamente --}}
    @if (isset($_GET['pedido']) && $_GET['pedido'] === 'ok')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Compra realizada!',
                    text: 'Tu pedido se ha procesado correctamente.',
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
    @endif

    {{-- SweetAlert si hubo error al procesar el pedido --}}
    @if (isset($_GET['pedido']) && $_GET['pedido'] === 'error')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al procesar el pedido',
                    text: 'Hubo un problema al finalizar la compra. Inténtalo de nuevo.',
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
    @endif
@endsection