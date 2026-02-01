<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

/**
 * Handles user management including CRUD operations and role assignments.
 */
class UserController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('admin/users/IndexUsers', [
            'users' => User::query()
                ->with('roles') // Cargamos los roles para mostrarlos en la tabla
                ->filter($request->only(['search']))
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleNames()->first(), // Obtenemos el nombre del rol
                    'created_at' => $user->created_at->format('d/m/Y'),
                ]),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email",
            'role' => ['required', new Enum(RoleEnum::class)],
            "password" => "required|string|min:6|confirmed"
        ]);

        // 1. Creamos el usuario sin la columna 'role'
        $user = User::create([
            "name" => $validated['name'],
            "email" => $validated['email'],
            "password" => Hash::make($validated['password']),
        ]);

        // 2. Asignamos el rol mediante Spatie
        $user->assignRole($validated['role']);

        return redirect()->back();
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email," . $user->id,
            'role' => ['required', new Enum(RoleEnum::class)],
            "password" => "nullable|string|min:6|confirmed",
        ]);

        $user->update([
            "name" => $validated['name'],
            "email" => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // 3. Sincronizamos el rol (esto elimina el anterior y pone el nuevo)
        $user->syncRoles($validated['role']);

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with([
                'message' => 'No puedes eliminarte a ti mismo',
                'status' => 'fail'
            ]);
        }

        $user->delete();

        return back()->with([
            'message' => 'Usuario eliminado con éxito',
            'status' => 'success'
        ]);
    }

    // TODO: Implement indexTrashed() for viewing soft-deleted users.

    // TODO: Implement restore() for recovering soft-deleted users.
}