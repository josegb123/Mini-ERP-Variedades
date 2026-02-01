<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case VENDEDOR = 'vendedor';
    case ESTANDAR = 'estandar';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::VENDEDOR => 'Vendedor',
            self::ESTANDAR => 'Usuario Estándar (Lectura)',
        };
    }
}