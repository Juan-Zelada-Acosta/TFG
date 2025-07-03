<?php
namespace App\Core;

use eftec\bladeone\BladeOne;

class View
{
    public static function render(string $template, array $data = []): void
    {
        $views = __DIR__ . '/../views';
        $cache = __DIR__ . '/../../cache';
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        echo $blade->run($template, $data);
    }
}
