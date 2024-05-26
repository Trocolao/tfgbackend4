<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(Request $request)
    {

        $request->validate([
            'numero_comensales' => 'required|integer|between:1,10',
            'dia' => 'required|date|after:today',
            'hora' => 'required|date_format:H:i',
            'turno' => 'required|in:comida,cena',
        ]);

        $validHours = $request->turno === 'comida' ? ['13:00', '13:30', '14:00', '14:30', '15:00', '15:30']
            : ['20:00', '20:30', '21:00', '21:30', '22:00', '22:30'];
        if (!in_array($request->hora, $validHours)) {
            return response()->json(['error' => 'Hora inválida para el turno seleccionado'], 422);
        }

        if (Reserva::where('user_id', Auth::id())->where('dia', $request->dia)->where('turno', $request->turno)->exists()) {
            return response()->json(['error' => 'Ya tiene una reserva para este turno'], 409);
        }

        $capacidadActual = Reserva::where('dia', $request->dia)
            ->where('hora', $request->hora)
            ->sum('numero_comensales');
        if ($capacidadActual + $request->numero_comensales > 30) {
            return response()->json(['error' => 'Capacidad superada para esta hora'], 409);
        }

        $reserva = new Reserva([
            'numero_comensales' => $request->numero_comensales,
            'dia' => $request->dia,
            'hora' => $request->hora,
            'turno' => $request->turno,
            'user_id' => Auth::id()  // Asignar el ID del usuario autenticado
        ]);
        $reserva->save();

        return $reserva;
    }
    public function misReservas()
    {
        $user_id = Auth::id();
        $reservas = Reserva::where('user_id', $user_id)->with(['user'])
            ->get();

        return $reservas;
    }

    public function index(Request $request)
    {
        $reservas = Reserva::all()-> load(['user']);
        return $reservas;
    }
    public function show(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id) -> load(['user']);
        return $reserva;
    }
    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        $user=Auth::user();
        if ($reserva->user_id !== Auth::id()&&$user->role==1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $request->validate([
            'numero_comensales' => 'required|integer|between:1,10',
            'dia' => 'required|date|after:today',
            'hora' => 'required|date_format:H:i',
            'turno' => 'required|in:comida,cena',
        ]);

        $reserva->update($request->all());
        return $reserva;
    }
    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);

        $user=Auth::user();
        if ($reserva->user_id !== Auth::id()&&$user->role==1) {
            return response()->json(['error' => 'No autorizado'], 403);
        }


        $reserva->delete();
        return response()->json(null, 204);
    }
    public function tieneReservaEnDiaYTurno($dia, $turno)
    {
        $user_id = Auth::id();
        return Reserva::where('user_id', $user_id)
            ->where('dia', $dia)
            ->where('turno', $turno)
            ->exists();
    }

}


