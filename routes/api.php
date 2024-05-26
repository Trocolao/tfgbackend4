<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PlatoController;
use App\Http\Controllers\ResenaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
    Route::get('meId', 'meId');

});
Route::controller(ResenaController::class)->group(function () {
    Route::post('resenas', 'store');
    Route::put('resenas/{id}', 'update');
    Route::delete('resenas/{id}', 'destroy');
    Route::get('resenas', 'index');
    Route::get('resenas/{id}', 'show');
});
Route::prefix('eventos')->group(function () {
    Route::get('/', [EventoController::class, 'index']);
    Route::get('/{id}', [EventoController::class, 'show']);
    Route::post('/', [EventoController::class, 'store']);
    Route::put('/{id}', [EventoController::class, 'actualizar']);
    Route::delete('/{id}', [EventoController::class, 'eliminar']);
    Route::post('/unirse/{eventoId}', [EventoController::class, 'unirse']);
    Route::post('/desapuntarse/{eventoId}', [EventoController::class, 'desapuntarse']);
    Route::get('/usuarioevento/{eventoId}', [EventoController::class, 'isTheUserInTheEvent']);
    Route::get('/eventolleno/{eventoId}', [EventoController::class, 'isEventFull']);

});
Route::get('eventos/{eventoId}/usuarios', [EventoController::class, 'usuariosApuntados']);

Route::controller(PlatoController::class)->group(function () {
    Route::post('platos', 'store');
    Route::put('platos/{id}', 'update');
    Route::delete('platos/{id}', 'destroy');
    Route::get('platos', 'index');
    Route::get('platos/{id}', 'show');
});

Route::controller(PedidoController::class)->group(function () {
    Route::post('pedidos', 'store');
    Route::put('pedidos/{id}', 'update');
    Route::delete('pedidos/{id}', 'destroy');
    Route::get('pedidos', 'index');
    Route::get('pedidos/{id}', 'show');
});

Route::controller(\App\Http\Controllers\ReservaController::class)->group(function () {
    Route::post('reservas', 'store');
    Route::put('reservas/{id}', 'update');
    Route::delete('reservas/{id}', 'destroy');
    Route::get('reservas', 'index');
    Route::get('reservas/{id}', 'show');
    Route::get('misreservas', 'misReservas');
    Route::get('reservas/{dia}/{turno}/verificar', 'tieneReservaEnDiaYTurno');

});
