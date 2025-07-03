<?php
namespace App\controllers;

use App\models\Newsletter;

class NewsletterController
{
    public static function guardar(): void
    {
        $correo = $_POST['email'] ?? '';
        $resultado = Newsletter::registrar($correo);

        if ($resultado === 'registrado') {
            header('Location: index.php?accion=inicio&newsletter=ok');
        } elseif ($resultado === 'existe') {
            header('Location: index.php?accion=inicio&newsletter=duplicado');
        } else {
            header('Location: index.php?accion=inicio&newsletter=error');
        }

        exit;
    }
}
