<?php

namespace App\controllers;

use App\Core\Conexion;
use eftec\bladeone\BladeOne;

class HomeController
{
    public function index(): void
    {
        $conn = Conexion::getConexion();

        $stmtAdmin = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'admin'");
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->get_result()->fetch_assoc();
        $mostrarAdvertenciaAdmin = $resultAdmin['total'] == 0;
        $stmtAdmin->close();

        $stmt = $conn->prepare("SELECT * FROM productos ORDER BY id DESC LIMIT 8");
        $stmt->execute();
        $result = $stmt->get_result();
        $productosRecientes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $blade = new BladeOne(__DIR__ . '/../views', __DIR__ . '/../../cache', BladeOne::MODE_AUTO);
        echo $blade->run('inicio', [
            'productosRecientes' => $productosRecientes,
            'mostrarAdvertenciaAdmin' => $mostrarAdvertenciaAdmin
        ]);
    }


}
