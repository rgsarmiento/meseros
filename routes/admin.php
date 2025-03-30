<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::resource('categorias', CategoriaController::class);

Route::resource('productos', ProductoController::class);

Route::get('ordenes/create', [OrdenController::class, 'create'])->name('ordenes.create');
Route::post('ordenes/agregar', [OrdenController::class, 'agregarAlCarrito'])->name('ordenes.agregarCarrito');
Route::get('ordenes/confirmar', [OrdenController::class, 'confirmar'])->name('ordenes.confirmar');
Route::post('ordenes/confirmarGuardar', [OrdenController::class, 'store'])->name('ordenes.store');
Route::post('ordenes/actualizar/{productoId}', [OrdenController::class, 'actualizarCarrito'])->name('ordenes.actualizarCarrito');
Route::post('ordenes/eliminar/{productoId}', [OrdenController::class, 'eliminarDelCarrito'])->name('ordenes.eliminarCarrito');
