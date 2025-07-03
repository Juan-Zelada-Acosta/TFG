<?php

namespace App\controllers;

use App\Core\Conexion;
use eftec\bladeone\BladeOne;

class ProductoController
{
    public static function index(): void
    {
        $conn = Conexion::getConexion();

        $busqueda = $_GET['buscar'] ?? '';
        if (!empty($busqueda)) {
            $stmt = $conn->prepare("SELECT * FROM productos WHERE nombre LIKE ?");
            $like = "%" . $busqueda . "%";
            $stmt->bind_param("s", $like);
        } else {
            $stmt = $conn->prepare("SELECT * FROM productos ORDER BY id DESC");
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        $productos = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($productos as &$producto) {
            $stmtStock = $conn->prepare("SELECT SUM(stock) AS total FROM producto_tallas WHERE id_producto = ?");
            $stmtStock->bind_param("i", $producto['id']);
            $stmtStock->execute();
            $resultStock = $stmtStock->get_result();
            $rowStock = $resultStock->fetch_assoc();
            $producto['stock'] = $rowStock['total'] ?? 0;
            $stmtStock->close();
        }

        $views = __DIR__ . '/../views';
        $cache = __DIR__ . '/../../cache';
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        echo $blade->run('productos', [
            'productos' => $productos,
            'busqueda' => $busqueda
        ]);
    }

    public static function mostrar(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $conn = Conexion::getConexion();
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo "ID de producto inválido.";
            return;
        }

        require_once __DIR__ . '/../includes/funciones.php';

        $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();

        if (!$producto) {
            echo "Producto no encontrado.";
            return;
        }

        $views = __DIR__ . '/../views';
        $cache = __DIR__ . '/../../cache';
        $blade = new \eftec\bladeone\BladeOne($views, $cache, \eftec\bladeone\BladeOne::MODE_AUTO);

        echo $blade->run('verProducto', ['producto' => $producto]);
    }

    public static function listar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $conexion = \App\Core\Conexion::getConexion();

        $busqueda = $_GET['buscar'] ?? '';
        $categoria = $_GET['categoria'] ?? 'todas';
        $coleccion = $_GET['coleccion'] ?? null;

        $sql = "SELECT p.*, COALESCE(SUM(pt.stock), 0) AS stock
            FROM productos p
            LEFT JOIN producto_tallas pt ON p.id = pt.id_producto";

        $condiciones = [];
        $parametros = [];
        $tipos = '';

        if (!empty($busqueda)) {
            $condiciones[] = "p.nombre LIKE ?";
            $parametros[] = '%' . $busqueda . '%';
            $tipos .= 's';
        }

        if ($categoria !== 'todas') {
            $condiciones[] = "p.categoria = ?";
            $parametros[] = $categoria;
            $tipos .= 's';
        }

        if (!empty($coleccion)) {
            $condiciones[] = "p.coleccion = ?";
            $parametros[] = $coleccion;
            $tipos .= 's';
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(' AND ', $condiciones);
        }

        $sql .= " GROUP BY p.id ORDER BY p.id DESC LIMIT 7";

        $stmt = $conexion->prepare($sql);

        if (!empty($parametros)) {
            $stmt->bind_param($tipos, ...$parametros);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);

        $blade = new \eftec\bladeone\BladeOne(__DIR__ . '/../views', __DIR__ . '/../../cache', \eftec\bladeone\BladeOne::MODE_AUTO);
        echo $blade->run('productos', [
            'productos' => $productos,
            'busqueda' => $busqueda
        ]);
    }
    public static function mostrarPorCategoria($categoria)
    {
        $conn = Conexion::getConexion();
        $coleccion = $_GET['coleccion'] ?? null;

        if ($coleccion) {
            $stmt = $conn->prepare("SELECT * FROM productos WHERE categoria = ? AND coleccion = ?");
            $stmt->bind_param("ss", $categoria, $coleccion);
        } else {
            $stmt = $conn->prepare("SELECT * FROM productos WHERE categoria = ?");
            $stmt->bind_param("s", $categoria);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $productos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $blade = new \eftec\bladeone\BladeOne(__DIR__ . '/../views', __DIR__ . '/../../cache', \eftec\bladeone\BladeOne::MODE_AUTO);
        echo $blade->run($categoria, ['productos' => $productos]);
    }

}





