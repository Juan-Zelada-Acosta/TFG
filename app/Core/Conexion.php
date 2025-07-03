<?php

namespace App\Core;

class Conexion
{
    private static $conexion;

    public static function getConexion()
    {
        if (!self::$conexion) {
            self::$conexion = new \mysqli('tfgprj-mysql', 'tiendauser', 'tienda123', 'tienda');

            if (self::$conexion->connect_error) {
                die('Error de conexión: ' . self::$conexion->connect_error);
            }

            self::$conexion->set_charset("utf8mb4");
        }

        return self::$conexion;
    }
}


