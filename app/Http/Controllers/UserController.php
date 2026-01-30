<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Definimos y ejecutamos la validación
        $rules = [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email",
            "password" => "required|string|max:255|min:6|confirmed"
        ];

        // Si esto falla, Laravel redirige automáticamente atrás 
        // y llena el objeto 'form.errors' en tu componente Vue.
        $validated = $request->validate($rules);

        // 2. Creamos el usuario
        User::create([
            "name" => $validated['name'],
            "email" => $validated['email'],
            "password" => Hash::make($validated['password']),
        ]);

        // 3. Redirigimos con un mensaje Flash
        // Este 'message' es el que recibirá tu componente en 'page.props.flash.message'
        return Inertia::back()->with('message', '¡Usuario creado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        //  Impedir que el usuario se elimine a sí mismo
        if (auth()->user()->id === $user->id) {
            return Inertia::flash(
                [
                    'message' => 'No se puede borrar el usuario activo',
                    'status' => 'fail',
                ]
            )
                ->back();
        }
        try {
            $user->delete();
            return Inertia::flash([
                'message' => '¡Usuario borrado con éxito!',
                'status' => 'success',
            ])
                ->back();
        } catch (\Throwable $th) {
            return Inertia::flash([
                'message' => 'Fallo al borrar al usuario',
                'status' => 'fail',
            ])
                ->back();
        }
    }
}
