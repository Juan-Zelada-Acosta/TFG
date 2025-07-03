<?php

/**
 * Devuelve el stock real total de un producto a partir de la tabla producto_tallas.
 *
 * @param int $idProducto ID del producto
 * @param mysqli $conn Conexión activa a la base de datos
 * @return int Stock total disponible
 */
function obtenerStockReal($idProducto, $conn)
{
    $stmt = $conn->prepare("SELECT SUM(stock) AS total FROM producto_tallas WHERE id_producto = ?");
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return isset($row['total']) ? (int) $row['total'] : 0;
}

/**
 * Devuelve el stock disponible para una talla específica.
 *
 * @param int $idProducto ID del producto
 * @param string $talla Talla a consultar (S, M, L, XL...)
 * @param mysqli $conn Conexión activa a la base de datos
 * @return int Stock disponible en esa talla
 */
function obtenerStockPorTalla($idProducto, $talla, $conn)
{
    $stmt = $conn->prepare("SELECT stock FROM producto_tallas WHERE id_producto = ? AND talla = ?");
    $stmt->bind_param("is", $idProducto, $talla);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return isset($row['stock']) ? (int) $row['stock'] : 0;
}

/**
 * Escapa una cadena para evitar XSS al imprimir en HTML.
 *
 * @param string|null $cadena Texto a escapar
 * @return string
 */
function limpiar($cadena)
{
    return htmlspecialchars($cadena ?? '', ENT_QUOTES, 'UTF-8');
}

