<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
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
                ->filter($request->only(['search']))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email",
            "role" => ["required", new Enum(UserRole::class)],
            "password" => "required|string|min:6|confirmed"
        ]);

        User::create([
            "name" => $validated['name'],
            "email" => $validated['email'],
            "role" => $validated['role'],
            "password" => Hash::make($validated['password']),
        ]);

        return redirect()->back();
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email," . $user->id,
            "role" => ["required", new Enum(UserRole::class)],
            "password" => "nullable|string|min:6|confirmed",
        ]);

        $user->fill([
            "name" => $validated['name'],
            "email" => $validated['email'],
            "role" => $validated['role'],
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back();
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with(['message' => 'No puedes eliminarte a ti mismo', 'status' => 'fail']);
        }

        $user->delete();
        return back()->with(['message' => 'Usuario eliminado con éxito', 'status' => 'success']);
    }

    // TODO: Implement indexTrashed() for viewing soft-deleted users.

    // TODO: Implement restore() for recovering soft-deleted users.
}