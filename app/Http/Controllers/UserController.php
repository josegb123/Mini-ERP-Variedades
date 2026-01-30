<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        $rules = [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email",
            "password" => "required|string|max:255|min:6|confirmed"
        ];

        $validated = $request->validate($rules);

        User::create([
            "name" => $validated['name'],
            "email" => $validated['email'],
            "password" => Hash::make($validated['password']),
        ]);

        return redirect()->back();
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
        $rules = [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email," . $user->id, //Manera facil de ignorar un el usuario actual
            "password" => "nullable|string|min:6|confirmed",
        ];

        $validated = $request->validate($rules);

        $data = [
            "name" => $validated['name'],
            "email" => $validated['email'],
        ];

        // Solo actualizamos el password si el usuario escribió uno nuevo
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->back();
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
