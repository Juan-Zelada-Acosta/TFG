<?php

namespace App\controllers;

use App\Core\Conexion;
use eftec\bladeone\BladeOne;

class LoginController
{
    public static function mostrarFormulario()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $error = $_SESSION['error_login'] ?? null;
        unset($_SESSION['error_login']);

        $views = __DIR__ . '/../views';
        $cache = __DIR__ . '/../../cache';
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        echo $blade->run('login', ['error' => $error]);
    }

    public static function procesar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        $conn = Conexion::getConexion();

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();

        if ($usuario) {
            $intentos = (int)$usuario['intentos_fallidos'];
            $ultimoIntento = $usuario['ultimo_intento'] ? strtotime($usuario['ultimo_intento']) : 0;
            $ahora = time();

            if ($intentos >= 5 && ($ahora - $ultimoIntento) < 300) {
                $_SESSION['error_login'] = 'Demasiados intentos fallidos. Intenta en unos minutos.';
                header('Location: index.php?accion=login');
                exit;
            }

            if (password_verify($contrasena, $usuario['contrasena'])) {
                $_SESSION['usuario'] = $usuario;

                $stmt = $conn->prepare("UPDATE usuarios SET intentos_fallidos = 0, ultimo_intento = NULL WHERE id = ?");
                $stmt->bind_param("i", $usuario['id']);
                $stmt->execute();
                $stmt->close();

                header('Location: index.php?accion=inicio');
                exit;
            } else {
                $stmt = $conn->prepare("UPDATE usuarios SET intentos_fallidos = intentos_fallidos + 1, ultimo_intento = NOW() WHERE id = ?");
                $stmt->bind_param("i", $usuario['id']);
                $stmt->execute();
                $stmt->close();

                $_SESSION['error_login'] = 'Correo electrónico o contraseña incorrectos.';
                header('Location: index.php?accion=login');
                exit;
            }
        } else {
            $_SESSION['error_login'] = 'Correo electrónico o contraseña incorrectos.';
            header('Location: index.php?accion=login');
            exit;
        }
    }

    public static function cerrar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: index.php?accion=inicio');
        exit;
    }
}

