<?php
namespace App\models;

use App\Core\Conexion;

class Newsletter
{
    public static function registrar(string $correo): string
    {
        $conn = Conexion::getConexion();
        
        $stmt = $conn->prepare("SELECT id FROM newsletter WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            return 'existe';
        }

        $stmt = $conn->prepare("INSERT INTO newsletter (correo) VALUES (?)");
        $stmt->bind_param("s", $correo);
        if ($stmt->execute()) {
            return 'registrado';
        }

        return 'error';
    }
}
