<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Imagene;
use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlatoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index(Request $request)
    {
        $peticiones = Plato::all()->load(['imagenes']);
        return $peticiones;
    }

    public function show(Request $request, $id)
    {
        $peticion = Plato::findOrFail($id)->load(['imagenes']);
        return $peticion;
    }

    public function store(Request $request)
    {
        $user=Auth::user();
        if($user->role!==2){
            return response()->json(['error' => 'No tienes permisos'], 403);
        }
        $validator = Validator::make($request-> all(),
            [
                'nombre' => 'required|max:255',
                'descripcion' => 'required',
                'categoria' => 'required',
                'precio' => 'required',
                // 'file' => 'required',
            ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator -> errors(), 401]);
        }
        $validator = Validator::make($request->all(),
            [
                'file' => 'required|max:4096'
            ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->error()], 402);
        }

        $input = $request->all();
        if($file = $request->file('file')){
            $name = $file->getClientOriginalName();
            Storage::put($name, file_get_contents($request->file('file')->getRealPath()));
            $file->move('storage/', $name);
            $input['file'] = $name;
        }

        //$input = $request->all();
        //$user=1; //harcodeamos el usuario

        $plato = new Plato($input);
        $plato -> nombre = $request->input('nombre');
        $plato -> descripcion = $request->input('descripcion');
        $plato -> categoria = $request->input('categoria');
        $plato -> precio = $request->input('precio');



        $res = $plato->save();

        $imgdb = new Imagene();
        $imgdb -> name = $input['file'];
        $imgdb -> file_path = 'storage/'.$input['file'];
        $imgdb -> plato_id = $plato->id;
        $imgdb -> save();

        if($res){
            return response() ->json(['message' => 'Plato creada satisfactoriamente', 'plato' => $plato],201);
        }
        return  response() -> json (['message' => 'Error creando la Plato'], 500);
    }


    public function update(Request $request, $id)
    {
        $user=Auth::user();
        if($user->role!==2){
            return response()->json(['error' => 'No tienes permisos'], 403);
        }
        $plato = Plato::findOrFail($id);
        $plato->update($request->all());
        return $plato;
    }

    public function destroy($id)
    {
        $user=Auth::user();
        if($user->role!==2){
            return response()->json(['error' => 'No tienes permisos'], 403);
        }
        $plato = Plato::findOrFail($id);
        $plato->delete();

        return response()->json(['message' => 'Plato eliminado correctamente'], 200);
    }
    //
}
