<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    // Listar todos los pedidos
    public function index()
    {
        $pedidos = Pedido::with('platos')->get();
        return $pedidos;
    }

    // Mostrar un pedido específico
    public function show($id)
    {
        $pedido = Pedido::with('platos')->findOrFail($id);
        return $pedido;
    }
    public function misPedidos(Request $request)
    {
        $user = Auth::user();

        $pedidos = Pedido::with('platos')->where('user_id', $user->id)->get();

        return response()->json($pedidos);
    }
    // Crear un nuevo pedido
    public function store(Request $request)
    {
        $request->validate([
            'platos' => 'required|array',
            'platos.*.id' => 'required|exists:platos,id',
            'platos.*.cantidad' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $pedido = new Pedido();
        $pedido->user_id = $user->id;
        $pedido->fecha = now();
        $pedido->total = 0; // Asignar un valor inicial

        // Guardar el pedido para obtener su ID
        $pedido->save();

        $total = 0;
        foreach ($request->platos as $plato) {
            $platoModelo = Plato::findOrFail($plato['id']);
            $total += $platoModelo->precio * $plato['cantidad'];
            $pedido->platos()->attach($plato['id'], ['cantidad' => $plato['cantidad']]);
        }

        $pedido->total = $total;
        $pedido->save();

        return response()->json($pedido->load('platos'), 201);
    }

    // Actualizar un pedido
    public function update(Request $request, $id)
    {

        $pedido = Pedido::findOrFail($id);

        $request->validate([
            'platos' => 'required|array',
            'platos.*.id' => 'required|exists:platos,id',
            'platos.*.cantidad' => 'required|integer|min:1',
        ]);
        $user=Auth::user();
        if($user->role!==2 && $pedido->user_id!==Auth::id()){
            return response()->json(['error' => 'No tienes permisos'], 403);
        }

        $pedido->platos()->detach();

        $total = 0;
        foreach ($request->platos as $plato) {
            $platoModelo = Plato::findOrFail($plato['id']);
            $total += $platoModelo->precio * $plato['cantidad'];
            $pedido->platos()->attach($plato['id'], ['cantidad' => $plato['cantidad']]);
        }

        $pedido->total = $total;
        $pedido->save();

        return response()->json($pedido->load('platos'));
    }

    // Eliminar un pedido
    public function destroy($id)
    {

        $pedido = Pedido::findOrFail($id);
        $user=Auth::user();
        if($user->role!==2 && $pedido->user_id!==Auth::id()){
            return response()->json(['error' => 'No tienes permisos'], 403);
        }
        $pedido->delete();

        return response()->json(null, 204);
    }
    // En App\Http\Controllers\PedidoController

    public function obtenerPlatosConCantidad($id)
    {
        $pedido = Pedido::with(['platos' => function($query) {
            $query->withPivot('cantidad');
        }])->findOrFail($id);

        return response()->json($pedido->platos);
    }

}
