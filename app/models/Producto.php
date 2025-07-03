<?php

namespace App\Models;

use App\Core\Conexion;

class Producto
{
    public static function obtenerTodos(): array
    {
        $conexion = Conexion::getConexion();
        $consulta = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
        return $consulta->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorCategoria(string $categoria): array
    {
        $conexion = Conexion::getConexion();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE categoria = ? ORDER BY id DESC");
        $consulta->bind_param("s", $categoria);
        $consulta->execute();
        $resultado = $consulta->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorColeccion(string $coleccion): array
    {
        $conexion = Conexion::getConexion();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE coleccion = ? ORDER BY id DESC");
        $consulta->bind_param("s", $coleccion);
        $consulta->execute();
        $resultado = $consulta->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public static function obtenerPorId(int $id): ?array
    {
        $conexion = Conexion::getConexion();
        $consulta = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $producto = $resultado->fetch_assoc();
        return $producto ?: null;
    }

    public static function obtenerStockPorTalla(int $idProducto): array
    {
        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT talla, stock FROM producto_tallas WHERE id_producto = ?");
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $tallas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $tallas[$fila['talla']] = $fila['stock'];
        }

        return $tallas;
    }
}

