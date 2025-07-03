<?php

namespace App\models;

use App\Core\Conexion;
use mysqli;

class Contacto
{
    public static function guardar(string $correo, string $mensaje): bool
    {
        $conn = Conexion::getConexion();
        $stmt = $conn->prepare("INSERT INTO contactos (correo, mensaje) VALUES (?, ?)");
        $stmt->bind_param("ss", $correo, $mensaje);
        return $stmt->execute();
    }
}
