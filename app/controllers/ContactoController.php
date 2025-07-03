<?php

namespace App\controllers;

use App\models\Contacto;

class ContactoController
{
    public static function guardar(): void
    {
        $correo = $_POST['correo'] ?? '';
        $mensaje = $_POST['mensaje'] ?? '';

        if (!empty($correo) && !empty($mensaje)) {
            Contacto::guardar($correo, $mensaje);
        }

        header('Location: index.php?accion=contacto&exito=1');
        exit;
    }
}
