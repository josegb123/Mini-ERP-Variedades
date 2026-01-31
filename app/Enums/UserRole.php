<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case SELLER = 'vendedor';
    case USER = 'user';

    // Método útil para etiquetas legibles
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::SELLER => 'Vendedor',
            self::USER => 'Usuario Estándar',
        };
    }
}