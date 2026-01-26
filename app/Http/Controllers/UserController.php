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
        // 1. Iniciamos la query (sin ejecutarla aún)
        $query = User::query();

        // 2. Aplicamos filtro de búsqueda si existe el parámetro
        // Usamos 'when' para mantener el código limpio y 'where/orWhere' agrupados
        $query->when($request->input('search'), function ($q, $search) {
            $q->where(function ($innerQuery) use ($search) {
                $innerQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        });

        // 3. Paginar resultados (Indispensable para un ERP)
        // Usamos 'withQueryString' para que el filtro 'search' persista al cambiar de página
        $users = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // 4. Devolvemos a Vue a través de Inertia
        return Inertia::render('admin/users/Index', [
            'users' => $users,
            'filters' => $request->only(['search']), // Para mantener el texto en el input del front
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
            "name" => "string|max:255",
            "email" => "email|max:255|unique:users",
            "password" => "string|max:255|min:6"
        ];

        $request->validate($rules);

        $user = User::create([
            "name" => $request->get('name'),
            "email" => $request->get('email'),
            "password" => Hash::make($request->get('passoword')),
        ]);

        dd($user);
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
    public function destroy(User $user)
    {
        //
    }
}
