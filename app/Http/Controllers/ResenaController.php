<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    public function create()
    {
        // Este método no se necesita en una API, ya que no se renderizarán vistas
        return response()->json(['message' => 'Endpoint no disponible'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
            'valoracion' => 'required|integer|min:1|max:5',
        ]);

        $resena = new Resena();
        $resena->titulo = $request->titulo;
        $resena->descripcion = $request->descripcion;
        $resena->valoracion = $request->valoracion;
        $resena->fecha = now();
        $resena->user_id = Auth::id();
        $resena->save();

        return response()->json(['message' => 'Reseña creada correctamente', 'resena' => $resena], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
            'valoracion' => 'required|integer|min:1|max:5',
        ]);

        $resena = Resena::findOrFail($id);
        if ($resena->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tienes permisos para editar esta reseña'], 403);
        }

        $resena->titulo = $request->titulo;
        $resena->descripcion = $request->descripcion;
        $resena->valoracion = $request->valoracion;
        $resena->fecha = now();
        $resena->save();
        return $resena;
    }

    public function destroy($id)
    {
        $resena = Resena::findOrFail($id);
        $user=Auth::user();
        if ($resena->user_id == Auth::id()||$user->role==2) {
            $resena->delete();

            return response()->json(['message' => 'Reseña eliminada correctamente'], 200);
        }
        return response()->json(['error' => 'No tienes permisos para eliminar esta reseña'], 403);


    }

    public function index()
    {
        $resenas = Resena::all()->load(['user']);
        return $resenas;
    }

    public function show($id)
    {
        $resena = Resena::findOrFail($id) -> load(['user']);

        return $resena;
    }
    //
}
