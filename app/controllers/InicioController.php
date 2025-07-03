<?php

namespace App\controllers;

use App\Models\Producto;
use eftec\bladeone\BladeOne;

class InicioController
{
    public static function mostrar()
    {
        $productos = Producto::obtenerPorColeccion('nueva');

        $views = __DIR__ . '/../views';
        $cache = __DIR__ . '/../../cache';
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        echo $blade->run('inicio', ['productos' => $productos]);
    }
}

