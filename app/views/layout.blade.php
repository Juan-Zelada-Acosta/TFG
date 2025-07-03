@php
use App\Core\Conexion;

$devolucionesPendientes = 0;

if (session_status() === PHP_SESSION_NONE) {
session_start();
}

if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin') {
$conn = Conexion::getConexion();
$query = "SELECT COUNT(*) AS total FROM pedidos WHERE estado = 'pendiente_devolucion'";
$resultado = $conn->query($query);
$fila = $resultado->fetch_assoc();
$devolucionesPendientes = $fila['total'] ?? 0;
}
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="shortcut icon" href="/favicon.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VEQL9KRXJJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-VEQL9KRXJJ');
    </script>
</head>

<body>
    <div class="top-bar py-1">
        <div class="w-100 overflow-hidden position-relative">
            <div class="scrolling-wrapper">
                <div class="scrolling-text">Born to last · Envíos a todo el mundo · Nueva colección disponible </div>
                <div class="scrolling-text">Born to last · Envíos a todo el mundo · Nueva colección disponible </div>
                <div class="scrolling-text">Born to last · Envíos a todo el mundo · Nueva colección disponible </div>
                <div class="scrolling-text">Born to last · Envíos a todo el mundo · Nueva colección disponible </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid d-flex justify-content-between align-items-center px-4">
            <div class="d-flex align-items-center">
                <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle nav-underline no-underline-hover" href="#" role="button">
                                <span>Productos</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php?accion=productos"><span class="dropdown-underline">Todos los productos</span></a></li>
                                <li><a class="dropdown-item" href="index.php?accion=camisetas"><span class="dropdown-underline">Camisetas</span></a></li>
                                <li><a class="dropdown-item" href="index.php?accion=sudaderas"><span class="dropdown-underline">Sudaderas</span></a></li>
                                <li><a class="dropdown-item" href="index.php?accion=pantalones"><span class="dropdown-underline">Pantalones</span></a></li>
                                <li><a class="dropdown-item" href="index.php?accion=calcetines"><span class="dropdown-underline">Calcetines</span></a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link nav-underline" href="index.php?accion=sobreNosotros">Sobre Nosotros</a>
                        </li>

                        @if(isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle nav-underline no-underline-hover" href="#" role="button" data-bs-toggle="dropdown">
                                Administración
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-blue" href="index.php?accion=agregarProducto"><span class="dropdown-underline">Agregar producto</span></a></li>
                                <li><a class="dropdown-item text-blue" href="index.php?accion=adminProductos"><span class="dropdown-underline">Gestionar productos</span></a></li>
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center text-blue" href="index.php?accion=verDevoluciones">
                                        <span class="dropdown-underline">Gestionar devoluciones</span>
                                        @if($devolucionesPendientes > 0)
                                        <span class="badge bg-danger ms-2">{{ $devolucionesPendientes }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <a class="navbar-brand position-absolute start-50 translate-middle-x" href="index.php?accion=inicio">
                <img src="/assets/img/logoStillwear.png" alt="Logo" class="logo-img">
            </a>

            <ul class="navbar-nav ms-auto">
                @if(isset($_SESSION['usuario']))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-underline no-underline-hover" href="#">
                        <span><i class="fa fa-user"></i> Hola, {{ $_SESSION['usuario']['nombre'] }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-blue" href="index.php?accion=miCuenta"><span class="dropdown-underline">Mi cuenta</span></a></li>
                        <li><a class="dropdown-item text-blue" href="index.php?accion=historial"><span class="dropdown-underline">Historial de pedidos</span></a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-blue" href="index.php?accion=cerrarSesion"><span class="dropdown-underline">Cerrar sesión</span></a></li>
                    </ul>
                </li>
                @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-underline no-underline-hover" href="#">
                        <span><i class="fa fa-user"></i> Cuenta</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-blue" href="index.php?accion=login"><span class="dropdown-underline">Iniciar sesión</span></a></li>
                        <li><a class="dropdown-item text-blue" href="index.php?accion=registro"><span class="dropdown-underline">Registrarse</span></a></li>
                    </ul>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="index.php?accion=carrito"><i class="fa fa-shopping-cart" style="color: #0085B4;"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <main>
        @yield('contenido')
    </main>

    <footer class="footer pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row align-items-start justify-content-between gy-4">
                <div class="col-12 col-lg-4">
                    <form class="d-flex newsletter-form" method="POST" action="index.php?accion=guardarNewsletter">
                        <input type="email" name="email" class="form-control" required placeholder="10% de dto en tu próximo pedido">
                        <button class="btn btn-outline-light ms-2" type="submit">Suscribirse</button>
                    </form>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <ul class="list-unstyled">
                                <li><a href="index.php?accion=contacto">Contacto</a></li>
                                <li><a href="index.php?accion=envios">Envíos</a></li>
                                <li><a href="index.php?accion=devoluciones">Devoluciones</a></li>
                                <li><a href="index.php?accion=guiaTallas">Guía de Tallas</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <ul class="list-unstyled">
                                <li><a href="index.php?accion=avisoLegal">Aviso Legal</a></li>
                                <li><a href="index.php?accion=terminos">Términos y Condiciones</a></li>
                                <li><a href="index.php?accion=privacidad">Política de Privacidad</a></li>
                                <li><a href="index.php?accion=trabajaConNosotros">Trabaja con Nosotros</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <h5 class="footer-title">Síguenos</h5>
                            <div class="d-flex gap-3">
                                <a href="https://www.instagram.com/" target="_blank">
                                    <i class="fab fa-instagram fa-lg text-blue"></i>
                                </a>
                                <a href="https://www.tiktok.com/" target="_blank">
                                    <i class="fab fa-tiktok fa-lg text-blue"></i>
                                </a>
                                <a href="https://www.youtube.com/" target="_blank">
                                    <i class="fab fa-youtube fa-lg text-blue"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-legal">
                <a href="index.php?accion=inicio" class="hover-underline">© 2025 STILLWEAR · Todos los derechos reservados.</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="/assets/js/main.js"></script>
    @if (isset($_GET['newsletter']) && $_GET['newsletter'] === 'ok')
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Gracias!',
            text: 'Te has suscrito correctamente a la newsletter.',
            confirmButtonColor: '#0085B4'
        });
    </script>
    @endif

    @if (isset($_GET['newsletter']) && $_GET['newsletter'] === 'duplicado')
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Ya registrado',
            text: 'Este correo ya está suscrito.',
            confirmButtonColor: '#0085B4'
        });
    </script>
    @endif

    @if (isset($_GET['newsletter']) && $_GET['newsletter'] === 'error')
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo procesar tu suscripción.',
            confirmButtonColor: '#0085B4'
        });
    </script>
    @endif
</body>

</html>