<?php

namespace App\controllers;

use App\Core\Conexion;

class SolicitudController
{
    public static function guardar()
    {
        $conn = Conexion::getConexion();
        $stmt = $conn->prepare("INSERT INTO solicitudes (nombre, correo, mensaje) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $_POST['nombre'], $_POST['correo'], $_POST['mensaje']);
        $stmt->execute();
        $stmt->close();

        header('Location: index.php?accion=trabajaConNosotros&exito=1');
        exit;
    }
}


