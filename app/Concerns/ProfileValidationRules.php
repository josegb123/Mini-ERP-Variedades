<?php

namespace App\Concerns;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function profileRules(?string $userId = null): array
    {
        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($userId),
            'role' => $this->roleRules(),
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate user roles based on Enum.
     */
    protected function roleRules(): array
    {
        return ['required', new Enum(UserRole::class)];
    }

    /**
     * Get the validation rules used to validate user emails.
     */
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