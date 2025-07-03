<?php

namespace App\controllers;

use App\Core\Conexion;
use eftec\bladeone\BladeOne;
use Dompdf\Dompdf;

class PedidoController
{
    public static function verHistorial()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario']['id'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id'];
        $conexion = Conexion::getConexion();

        $stmt = $conexion->prepare("SELECT id, fecha, total, estado FROM pedidos WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedidos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $blade = new \eftec\bladeone\BladeOne(__DIR__ . '/../views', __DIR__ . '/../../cache', \eftec\bladeone\BladeOne::MODE_AUTO);
        echo $blade->run('historial', ['pedidos' => $pedidos]);
    }


    public static function descargarFactura()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $id = $_GET['id'] ?? null;
        $idUsuario = $_SESSION['usuario']['id'];

        if (!$id || !is_numeric($id)) {
            echo "ID inválido.";
            return;
        }

        $conexion = Conexion::getConexion();

        $stmt = $conexion->prepare("SELECT * FROM pedidos WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param("ii", $id, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();
        $stmt->close();

        if (!$pedido) {
            echo "Pedido no encontrado.";
            return;
        }

        $stmtDetalles = $conexion->prepare("SELECT * FROM pedido_productos WHERE id_pedido = ?");
        $stmtDetalles->bind_param("i", $id);
        $stmtDetalles->execute();
        $resultDetalles = $stmtDetalles->get_result();
        $productos = $resultDetalles->fetch_all(MYSQLI_ASSOC);
        $stmtDetalles->close();

        $html = '
    <style>
        body {
            font-family: "Poppins", Arial, sans-serif;
            color: #333;
            font-size: 14px;
            padding: 30px;
            position: relative;
        }
        .header {
            margin-bottom: 20px;
        }
        .brand {
            font-size: 26px;
            font-weight: bold;
            color: #0085B4;
        }
        h1 {
            color: #0085B4;
            font-size: 22px;
            margin: 10px 0 5px;
        }
        .thanks {
            font-size: 14px;
            color: #0085B4;
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 4px 0;
        }
        .section-title {
            color: #0085B4;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #0085B4;
            color: #fff;
            padding: 8px;
            text-align: left;
        }
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        .total {
            text-align: right;
            font-size: 16px;
            margin-top: 10px;
            font-weight: bold;
            color: #0085B4;
        }
        .footer {
            border-top: 1px solid #ccc;
            font-size: 12px;
            color: #888;
            text-align: center;
            margin-top: 40px;
            padding-top: 10px;
        }
    </style>';

        $html .= '<div class="header">
    <div class="brand">STILLWEAR</div>
    <h1>Factura del Pedido #' . $pedido['id'] . '</h1>
    <div class="thanks">Gracias por tu compra</div>
</div>';

        $html .= '<div class="info">
    <p class="section-title">Datos del Pedido</p>
    <p><strong>Fecha:</strong> ' . htmlspecialchars($pedido['fecha']) . '</p>
    <p><strong>Estado:</strong> ' . htmlspecialchars($pedido['estado']) . '</p>

    <p class="section-title">Enviado a</p>
    <p><strong>Nombre:</strong> ' . htmlspecialchars($pedido['nombre']) . '</p>
    <p><strong>Dirección:</strong> ' . nl2br(htmlspecialchars($pedido['direccion'])) . '</p>
</div>';

        $html .= '<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>';



        $total = 0;
        foreach ($productos as $p) {
            $subtotal = $p['cantidad'] * $p['precio_unitario'];
            $html .= "<tr><td>{$p['nombre_producto']}</td><td>{$p['cantidad']}</td><td>{$p['precio_unitario']}€</td><td>{$subtotal}€</td></tr>";
            $total += $subtotal;
        }

        $html .= '</tbody></table>';
        $html .= '<div class="total">Total: ' . number_format($total, 2) . '€</div>';

        $html .= '<div class="footer">
    STILLWEAR · Calle Serrano 45, 28001 Madrid · info@stillwear.com · www.stillwear.com
        </div>';



        $pdf = new Dompdf();
        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream("stillwear_factura_{$pedido['id']}.pdf");
    }

    public static function verDetalle()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario']['id'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $idPedido = $_GET['id'] ?? null;

        if (!$idPedido) {
            header('Location: index.php?accion=historial');
            exit;
        }

        $conn = Conexion::getConexion();

        $stmt = $conn->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedido = $result->fetch_assoc();
        $stmt->close();

        if (!$pedido) {
            header('Location: index.php?accion=historial');
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM pedido_productos WHERE id_pedido = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $pedido['productos'] = $productos;

        $blade = new BladeOne(__DIR__ . '/../views', __DIR__ . '/../../cache', BladeOne::MODE_AUTO);
        echo $blade->run('detallePedido', ['pedido' => $pedido]);
    }


    public static function verTodos()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $idUsuario = $_SESSION['usuario']['id'];
        $conexion = Conexion::getConexion();

        $stmt = $conexion->prepare("SELECT * FROM pedidos WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $pedidos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $blade = new BladeOne(__DIR__ . '/../views', __DIR__ . '/../../cache', BladeOne::MODE_AUTO);
        echo $blade->run('listaPedidos', ['pedidos' => $pedidos]);
    }
    public static function devolverPedido()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'])) {
            if (session_status() === PHP_SESSION_NONE)
                session_start();

            $idPedido = intval($_POST['id_pedido']);
            $idUsuario = $_SESSION['usuario']['id'];
            $conn = Conexion::getConexion();

            $stmt = $conn->prepare("SELECT id FROM pedidos WHERE id = ? AND id_usuario = ?");
            $stmt->bind_param("ii", $idPedido, $idUsuario);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 0) {
                $stmt->close();
                $_SESSION['error_devolucion'] = "El pedido no existe o no te pertenece.";
                header("Location: index.php?accion=historial");
                exit;
            }
            $stmt->close();

            $update = $conn->prepare("UPDATE pedidos SET estado = 'pendiente_devolucion' WHERE id = ?");
            $update->bind_param("i", $idPedido);
            $update->execute();
            $update->close();

            $_SESSION['mensaje_devolucion'] = "El pedido ha sido marcado como pendiente de devolución.";
            header("Location: index.php?accion=historial");
            exit;
        }
    }
    public static function verSolicitudesDevolucion()
    {
        $conn = Conexion::getConexion();
        $result = $conn->query("
        SELECT pedidos.*, usuarios.nombre 
        FROM pedidos 
        JOIN usuarios ON pedidos.id_usuario = usuarios.id 
        WHERE pedidos.estado = 'pendiente_devolucion'
    ");

        $solicitudes = $result->fetch_all(MYSQLI_ASSOC);

        $blade = new \eftec\bladeone\BladeOne(
            __DIR__ . '/../views',
            __DIR__ . '/../../cache',
            \eftec\bladeone\BladeOne::MODE_AUTO
        );

        echo $blade->run('admin.devoluciones', ['solicitudes' => $solicitudes]);
    }

    public static function aprobarDevolucion()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id']))
            exit;

        $idPedido = intval($_GET['id']);
        $conn = Conexion::getConexion();

        $stmt = $conn->prepare("SELECT id_producto, cantidad, talla FROM pedido_productos WHERE id_pedido = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($productos as $item) {
            $stmt = $conn->prepare("UPDATE producto_tallas SET stock = stock + ? WHERE id_producto = ? AND talla = ?");
            $stmt->bind_param("iis", $item['cantidad'], $item['id_producto'], $item['talla']);
            $stmt->execute();
            $stmt->close();
        }

        $stmt = $conn->prepare("UPDATE pedidos SET estado = 'devuelto' WHERE id = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $stmt->close();

        header('Location: index.php?accion=verDevoluciones');
        exit;
    }

    public static function rechazarDevolucion()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id']))
            exit;

        $idPedido = intval($_GET['id']);
        $conn = Conexion::getConexion();

        $stmt = $conn->prepare("UPDATE pedidos SET estado = 'completado' WHERE id = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $stmt->close();

        header('Location: index.php?accion=verDevoluciones');
        exit;
    }
}
