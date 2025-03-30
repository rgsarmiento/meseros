<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::post('categorias/replace-all', [CategoriaController::class, 'replaceAll']);

Route::post('productos/replace-all', [ProductoController::class, 'replaceAll']);