<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::post('categorias/replace-all', [CategoriaController::class, 'replaceAll']);

Route::post('productos/replace-all', [ProductoController::class, 'replaceAll']);

Route::get('/ordenes/pendientes', [OrdenController::class, 'obtenerOrdenesPendientes']);
Route::put('/ordenes/{llave}/estado', [OrdenController::class, 'actualizarEstadoPorLlave']);