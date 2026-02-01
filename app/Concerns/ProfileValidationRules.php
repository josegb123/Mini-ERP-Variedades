<?php

namespace App\Concerns;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

trait ProfileValidationRules
{
    /**
     * Obtiene las reglas de validación para el perfil.
     * * @param string|null $userId ID del usuario para ignorar en el unique.
     * @param bool $includeRole Si se debe validar el campo de rol.
     */
    protected function profileRules(?string $userId = null, bool $includeRole = true): array
    {
        $rules = [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($userId),
        ];

        // Solo agregamos el rol si es explícitamente requerido
        if ($includeRole) {
            $rules['role'] = $this->roleRules();
        }

        return $rules;
    }

    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Validación de rol: 'sometimes' permite que el campo sea opcional
     * si no viene en la petición.
     */
    protected function roleRules(): array
    {
        return ['sometimes', 'required', new Enum(RoleEnum::class)];
    }

    protected function emailRules(?string $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $userId === null
            ? Rule::unique(User::class)
            : Rule::unique(User::class)->ignore($userId),
        ];
    }
}