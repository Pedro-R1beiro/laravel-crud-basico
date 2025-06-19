<?php

use App\Http\Controllers\MachinesController;
use Illuminate\Support\Facades\Route;

/*Route::get('/listar-maquinas', [\App\Http\Controllers\MachinesController::class, 'index']);
Route::get('/visualizar-maquina/{id}', [\App\Http\Controllers\MachinesController::class, 'show']);
Route::get('/cadastrar-maquina', [\App\Http\Controllers\MachinesController::class, 'create']);
Route::post('/cadastrar-maquina', [\App\Http\Controllers\MachinesController::class, 'store']);*/

Route::resource('machines', MachinesController::class);