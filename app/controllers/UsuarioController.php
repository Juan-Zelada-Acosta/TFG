<?php

namespace App\controllers;

use App\Core\Conexion;
use eftec\bladeone\BladeOne;

class UsuarioController
{
    public static function miCuenta()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $mensaje = $_GET['password'] ?? null;

        $views = __DIR__ . '/../views';
        $cache = __DIR__ . '/../../cache';
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        echo $blade->run('miCuenta', ['mensaje' => $mensaje]);
    }

    public static function cambiarPassword()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $actual = $_POST['actual'] ?? '';
        $nueva = $_POST['nueva'] ?? '';
        $idUsuario = $_SESSION['usuario']['id'];

        $conexion = Conexion::getConexion();

        $stmt = $conexion->prepare("SELECT contrasena FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();

        if (!$usuario || !password_verify($actual, $usuario['contrasena'])) {
            header("Location: index.php?accion=miCuenta&password=error");
            exit;
        }

        $nuevaHash = password_hash($nueva, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("UPDATE usuarios SET contrasena = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevaHash, $idUsuario);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php?accion=miCuenta&password=cambiada");
        exit;
    }



    public static function registrarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $fechaNacimiento = $_POST['fecha'] ?? '';

            if (empty($nombre) || empty($email) || empty($password) || empty($fechaNacimiento)) {
                header("Location: index.php?accion=registro&error=campos");
                exit;
            }

            $conexion = Conexion::getConexion();

            $stmtCheck = $conexion->prepare("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'admin'");
            $stmtCheck->execute();
            $result = $stmtCheck->get_result()->fetch_assoc();
            $hayAdmin = $result['total'] > 0;
            $stmtCheck->close();

            $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->close();
                header("Location: index.php?accion=registro&error=email");
                exit;
            }
            $stmt->close();

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $rol = $hayAdmin ? 'cliente' : 'admin';

            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, contrasena, fecha_nacimiento, rol) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nombre, $email, $hash, $fechaNacimiento, $rol);
            $stmt->execute();
            $usuarioId = $stmt->insert_id;
            $stmt->close();

            if (session_status() === PHP_SESSION_NONE)
                session_start();

            $stmt = $conexion->prepare("SELECT id, nombre, email, rol FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $usuarioId);
            $stmt->execute();
            $result = $stmt->get_result();
            $usuario = $result->fetch_assoc();
            $stmt->close();

            $_SESSION['usuario'] = $usuario;

            header("Location: index.php?accion=miCuenta");
            exit;
        }
    }


    public static function eliminarCuenta()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?accion=login');
            exit;
        }

        $id = $_SESSION['usuario']['id'];
        $conexion = Conexion::getConexion();

        $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        session_destroy();
        header('Location: index.php?accion=inicio');
        exit;
    }
}
