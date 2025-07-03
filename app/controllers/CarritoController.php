<?php

namespace App\controllers;

use App\Core\Conexion;
use App\Models\Producto;
use App\Core\View;

class CarritoController
{
    public static function add()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }


        $id_usuario = $_SESSION['usuario']['id'];
        $id_producto = $_POST['id_producto'] ?? null;
        $talla = $_POST['talla'] ?? null;
        $cantidadNueva = $_POST['cantidad'] ?? 1;

        if (!$id_producto || !$talla || !is_numeric($cantidadNueva)) {
            echo 'error_parametros';
            return;
        }

        $conn = Conexion::getConexion();

        $stmt = $conn->prepare("SELECT stock FROM producto_tallas WHERE id_producto = ? AND talla = ?");
        $stmt->bind_param("is", $id_producto, $talla);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stockDisponible = $row['stock'] ?? 0;
        $stmt->close();

        $stmt = $conn->prepare("SELECT cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ? AND talla = ?");
        $stmt->bind_param("iis", $id_usuario, $id_producto, $talla);
        $stmt->execute();
        $result = $stmt->get_result();
        $rowCarrito = $result->fetch_assoc();
        $cantidadExistente = $rowCarrito['cantidad'] ?? 0;
        $stmt->close();

        if ($cantidadExistente + $cantidadNueva > $stockDisponible) {
            echo 'stock_insuficiente';
            return;
        }

        if ($cantidadExistente > 0) {
            $stmt = $conn->prepare("UPDATE carrito SET cantidad = cantidad + ? WHERE id_usuario = ? AND id_producto = ? AND talla = ?");
            $stmt->bind_param("iiis", $cantidadNueva, $id_usuario, $id_producto, $talla);
        } else {
            $stmt = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad, talla) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiis", $id_usuario, $id_producto, $cantidadNueva, $talla);
        }

        $stmt->execute();
        $stmt->close();

        echo 'ok';
    }


    public static function mostrar()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $conn = Conexion::getConexion();
        $id_usuario = $_SESSION['usuario']['id'];

        $stmt = $conn->prepare("
        SELECT c.id AS id_carrito, c.id_producto, c.cantidad, c.talla, p.nombre, p.precio, p.imagen
        FROM carrito c
        JOIN productos p ON c.id_producto = p.id
        WHERE c.id_usuario = ?
    ");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $productos_crudos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $productos = array_map(function ($item) {
            $item['id'] = $item['id_carrito'];
            unset($item['id_carrito']);
            return $item;
        }, $productos_crudos);

        View::render('carrito', ['productos' => $productos]);
    }


    public static function eliminar()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario'])) {
            echo "sin_sesion";
            return;
        }

        $id_usuario = $_SESSION['usuario']['id'];
        $id_carrito = $_POST['id_carrito'] ?? null;

        if ($id_carrito && is_numeric($id_carrito)) {
            $conn = Conexion::getConexion();

            $stmt = $conn->prepare("DELETE FROM carrito WHERE id = ? AND id_usuario = ?");
            $stmt->bind_param("ii", $id_carrito, $id_usuario);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "ok";
            } else {
                echo "error_delete";
            }

            $stmt->close();
            return;
        }

        echo "error_param";
    }




    public static function finalizarCompra()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $conn = Conexion::getConexion();
        $id_usuario = $_SESSION['usuario']['id'];

        $stmt = $conn->prepare("
            SELECT c.id_producto, c.cantidad, c.talla, p.nombre, p.precio, p.imagen
            FROM carrito c
            JOIN productos p ON c.id_producto = p.id
            WHERE c.id_usuario = ?
        ");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $carrito = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($carrito)) {
            header('Location: index.php?accion=carrito');
            exit;
        }

        View::render('finalizarCompra', ['carrito' => $carrito]);
    }

    public static function procesarPedido()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $conn = Conexion::getConexion();
        $conn->begin_transaction();

        try {
            $idUsuario = $_SESSION['usuario']['id'];
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $tarjeta = str_replace(' ', '', $_POST['numero_tarjeta'] ?? '');
            $caducidad = trim($_POST['caducidad'] ?? '');
            $cvv = trim($_POST['cvv'] ?? '');

            // Validaciones básicas
            if (!$nombre || !$direccion || !$tarjeta || !$caducidad || !$cvv) {
                throw new \Exception("Todos los campos son obligatorios.");
            }

            $stmt = $conn->prepare("
            SELECT c.id_producto, c.cantidad, c.talla, p.nombre, p.precio, p.imagen
            FROM carrito c
            JOIN productos p ON c.id_producto = p.id
            WHERE c.id_usuario = ?
        ");
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $carrito = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            if (empty($carrito)) {
                throw new \Exception("El carrito está vacío.");
            }

            $total = 0;
            foreach ($carrito as $item) {
                $stmt = $conn->prepare("SELECT stock FROM producto_tallas WHERE id_producto = ? AND talla = ?");
                $stmt->bind_param("is", $item['id_producto'], $item['talla']);
                $stmt->execute();
                $stockResult = $stmt->get_result();
                $row = $stockResult->fetch_assoc();
                $stock = $row['stock'] ?? null;
                $stmt->close();

                if ($stock === null || $item['cantidad'] > $stock) {
                    throw new \Exception("Stock insuficiente para {$item['nombre']} (Talla {$item['talla']})");
                }

                $total += $item['precio'] * $item['cantidad'];
            }

            $estadoInicial = 'completado'; 
            $tarjetaHash = password_hash($tarjeta, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO pedidos (id_usuario, nombre, direccion, tarjeta, total, estado) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssds", $idUsuario, $nombre, $direccion, $tarjetaHash, $total, $estadoInicial);
            $stmt->execute();
            $idPedido = $conn->insert_id;
            $stmt->close();

            foreach ($carrito as $item) {
                $stmt = $conn->prepare("INSERT INTO pedido_productos (id_pedido, id_producto, cantidad, talla, precio_unitario, nombre_producto, imagen_producto) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiisdss", $idPedido, $item['id_producto'], $item['cantidad'], $item['talla'], $item['precio'], $item['nombre'], $item['imagen']);
                $stmt->execute();
                $stmt->close();

                $stmt = $conn->prepare("UPDATE producto_tallas SET stock = stock - ? WHERE id_producto = ? AND talla = ?");
                $stmt->bind_param("iis", $item['cantidad'], $item['id_producto'], $item['talla']);
                $stmt->execute();
                $stmt->close();
            }

            $stmt = $conn->prepare("DELETE FROM carrito WHERE id_usuario = ?");
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $stmt->close();

            $conn->commit();

            header("Location: index.php?accion=inicio&pedido=ok");
            exit;

        } catch (\Exception $e) {
            $conn->rollback();

            header("Location: index.php?accion=finalizarCompra&pedido=error");
            exit;
        }
    }




}



