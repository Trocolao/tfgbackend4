<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }


    public function store(Request $request)
    {
        $user=Auth::user();
        if($user->role!==2){
            return response()->json(['error' => 'No tienes permisos'], 403);
        }
        $validator = Validator::make($request->all(),
            [
                'nombre' => 'required|max:255',
                'descripcion' => 'required',
                'fecha' => 'required',
                'limite_participantes' => 'required',
                //'file' => 'required',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $validator = Validator::make($request->all(),
            [
                'file' => 'required|mimes:png,jpg|max:4096',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 402);
        }

        $input = $request->all();
        if ($file = $request->file('file')) {
            $name = $file->getClientOriginalName();
            Storage::put($name, file_get_contents($request->file('file')->getRealPath()));
            $file->move('storage/', $name);
            $input['file'] = $name;
        }
        $evento = new Evento();
        $evento->nombre = $request->input('nombre');
        $evento->descripcion = $request->input('descripcion');
        $evento->fecha = $request->input('fecha');
        $evento->limite_participantes = $request->input('limite_participantes');
        $evento->participantes = 0;

        $res = $evento->save();

        $imgdb = new File();
        $imgdb->name = $input['file'];
        $imgdb->file_path = 'storage/'.$input['file'];
        $imgdb->evento_id = $evento->id;
        $imgdb->save();

        return $evento;

        if ($res) {

            return response()->json(['message' => 'Evento creada satisfactioriamente', 'evento' => $evento], 201);
        }
        return response()->json(['message' => 'Error creando la peticion'], 500);
    }

    public function actualizar(Request $request, $id)
    {
        $user=Auth::user();
        if($user->role!==2){
            return response()->json(['error' => 'No tienes permisos'], 403);
        }
        $evento = Evento::findOrFail($id);
        $evento->update($request->all());
        return $evento;
    }

    public function eliminar($id)
    {
        $user=Auth::user();
        if($user->role!==2){
            return response()->json(['error' => 'No tienes permisos'], 403);
        }
        $evento = Evento::findOrFail($id);
        $evento->delete();

        return response()->json(['message' => 'Evento eliminado correctamente'], 200);
    }
    public function unirse(Request $request, $eventoId)
    {
        $evento = Evento::findOrFail($eventoId);

        if ($evento->users()->where('user_id', auth()->id())->exists()) {
            return response()->json(['error' => 'Ya estás unido a este evento'], 400);
        }

        if ($evento->users()->count() >= $evento->limite_participantes) {
            return response()->json(['error' => 'El evento ya tiene el número máximo de participantes'], 400);
        }

        $evento->users()->attach(auth()->id());
        $evento->increment('participantes'); // Incrementar directamente en la base de datos

        return $evento;
    }

    public function desapuntarse(Request $request, $eventoId)
    {
        $evento = Evento::findOrFail($eventoId);

        if (!$evento->users()->where('user_id', auth()->id())->exists()) {
            return response()->json(['error' => 'No estás unido a este evento'], 400);
        }

        $evento->users()->detach(auth()->id());
        $evento->decrement('participantes'); // Decrementar directamente en la base de datos

        return $evento;
    }

    public function index()
    {
        $eventos = Evento::all()->load(['files']);

        return $eventos;
    }

    public function show(Request $request, $id)
    {
        $peticion = Evento::findOrFail($id) -> load([ 'files']);
        return $peticion;
    }
    public function usuariosApuntados($eventoId)
    {
        $evento = Evento::findOrFail($eventoId);
        $usuarios = $evento->users()->get(); // Obtener todos los usuarios apuntados al evento
        return $usuarios;
    }
    public function isTheUserInTheEvent($eventoId) {
        $user = Auth::user(); // Obtiene el usuario autenticado
        $evento = Evento::find($eventoId); // Busca el evento por ID

        if ($user->eventos()->where('evento_id', $eventoId)->exists()) {
            return true; // El usuario está en el evento, retorna true
        } else {
            return false; // El usuario no está en el evento, retorna false
        }
    }

    public function isEventFull($eventoId) {
        $evento = Evento::find($eventoId);
        if ($evento->participantes >= $evento->limite_participantes) {
            return true; // El evento está lleno, retorna true
        } else {
            return false;
        }
    }

}
