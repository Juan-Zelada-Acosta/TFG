<?php

require __DIR__ . '/../vendor/autoload.php';

use App\controllers\{
    InicioController,
    ProductoController,
    AdminController,
    CarritoController,
    PedidoController,
    LoginController,
    UsuarioController,
    SolicitudController,
    ContactoController
};
use eftec\bladeone\BladeOne;
use App\Helpers\Auth;
use App\Core\Conexion;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'cambiarContrasena') {
    $contrasenaActual = $_POST['contrasena_actual'] ?? '';
    $nuevaContrasena = $_POST['nueva_contrasena'] ?? '';
    $idUsuario = $_SESSION['usuario']['id'] ?? null;

    if ($idUsuario) {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT contrasena FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        if (!$usuario || !password_verify($contrasenaActual, $usuario['contrasena'])) {
            header('Location: index.php?accion=miCuenta&error=actual');
            exit;
        }

        $nuevaHash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        $update = $conexion->prepare("UPDATE usuarios SET contrasena = ? WHERE id = ?");
        $update->bind_param("si", $nuevaHash, $idUsuario);
        $update->execute();

        header('Location: index.php?accion=miCuenta&password=cambiada');
        exit;
    } else {
        header('Location: index.php?accion=login');
        exit;
    }
}

$views = __DIR__ . '/../app/views';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

$accion = $_GET['accion'] ?? 'inicio';

switch ($accion) {
    case 'inicio':
        (new \App\controllers\HomeController())->index();
        break;
    case 'productos':
        ProductoController::index();
        break;
    case 'registrarUsuario':
        UsuarioController::registrarUsuario();
        break;
    case 'eliminarCarrito':
        CarritoController::eliminar();
        break;

    case 'eliminarCuenta':
        UsuarioController::eliminarCuenta();
        break;
    case 'producto':
        ProductoController::mostrar();
        break;
    case 'login':
        LoginController::mostrarFormulario();
        break;
    case 'procesarLogin':
        LoginController::procesar();
        break;
    case 'cerrarSesion':
        LoginController::cerrar();
        break;
    case 'verPedidos':
        PedidoController::verTodos();
        break;
    case 'agregarProducto':
        if (Auth::esAdmin()) {
            echo $blade->run('admin.agregarProducto');
        } else {
            $_SESSION['acceso_denegado'] = 'No tienes permisos para acceder aquí.';
            header('Location: index.php?accion=inicio');
        }
        break;
    case 'productosFiltrar':
        ProductoController::listar();
        break;

    case 'guardarProducto':
        Auth::esAdmin() ? (new AdminController())->guardarProducto() : accesoDenegado();
        break;
    case 'adminProductos':
        Auth::esAdmin() ? AdminController::listarProductos() : accesoDenegado();
        break;
    case 'verProducto':
        require_once __DIR__ . '/../app/controllers/ProductoController.php';
        ProductoController::mostrar();
        break;

    case 'editarProducto':
        Auth::esAdmin() ? AdminController::editarProducto() : accesoDenegado();
        break;
    case 'guardarEdicion':
        Auth::esAdmin() ? AdminController::guardarEdicion() : accesoDenegado();
        break;
    case 'eliminarProducto':
        Auth::esAdmin() ? AdminController::eliminarProducto() : accesoDenegado();
        break;
    case 'addCarrito':
        CarritoController::add();
        break;
    case 'carrito':
        CarritoController::mostrar();
        break;
    case 'eliminar_carrito':
        CarritoController::eliminar();
        break;
    case 'finalizarCompra':
        CarritoController::finalizarCompra();
        break;
    case 'procesarPedido':
        CarritoController::procesarPedido();
        break;
    case 'historial':
        PedidoController::verHistorial();
        break;
    case 'descargarFactura':
        PedidoController::descargarFactura();
        break;
    case 'detallePedido':
        PedidoController::verDetalle();
        break;
    case 'miCuenta':
        UsuarioController::miCuenta();
        break;
    case 'devolverPedido':
        PedidoController::devolverPedido();
        break;
    case 'cambiarPassword':
        UsuarioController::cambiarPassword();
        break;
    case 'enviarSolicitud':
        SolicitudController::guardar();
        break;
    case 'enviarContacto':
        ContactoController::guardar();
        break;

    case 'sobreNosotros':
    case 'registro':
    case 'recuperar':
    case 'avisoLegal':
    case 'privacidad':
    case 'terminos':
    case 'envios':
    case 'devoluciones':
    case 'contacto':
    case 'guiaTallas':
    case 'trabajaConNosotros':
        echo $blade->run($accion);
        break;
    case 'camisetas':
        ProductoController::mostrarPorCategoria('camisetas');
        break;

    case 'sudaderas':
        ProductoController::mostrarPorCategoria('sudaderas');
        break;

    case 'pantalones':
        ProductoController::mostrarPorCategoria('pantalones');
        break;

    case 'calcetines':
        ProductoController::mostrarPorCategoria('calcetines');
        break;

    case 'verDevoluciones':
        if (Auth::esAdmin()) {
            PedidoController::verSolicitudesDevolucion();
        } else {
            $_SESSION['acceso_denegado'] = 'No tienes permisos para acceder aquí.';
            header('Location: index.php?accion=inicio');
        }
        break;

    case 'aprobarDevolucion':
        if (Auth::esAdmin()) {
            PedidoController::aprobarDevolucion();
        } else {
            $_SESSION['acceso_denegado'] = 'No tienes permisos para acceder aquí.';
            header('Location: index.php?accion=inicio');
        }
        break;

    case 'rechazarDevolucion':
        if (Auth::esAdmin()) {
            PedidoController::rechazarDevolucion();
        } else {
            $_SESSION['acceso_denegado'] = 'No tienes permisos para acceder aquí.';
            header('Location: index.php?accion=inicio');
        }
        break;
    case 'guardarNewsletter':
        \App\controllers\NewsletterController::guardar();
        break;

    default:
        InicioController::mostrar();
        break;
}

function accesoDenegado()
{
    $_SESSION['acceso_denegado'] = 'No tienes permisos para realizar esta acción.';
    header('Location: index.php?accion=inicio');
    exit;
}
