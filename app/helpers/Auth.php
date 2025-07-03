<?php

namespace App\Helpers;

class Auth
{
    public static function esAdmin(): bool
    {
        return isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] === 'admin';
    }
}

