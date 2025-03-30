<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los productos de la base de datos
        $productos = Producto::with('categoria')->get();
        return view('productos.index',
        compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function replaceAll(Request $request)
{
    // Valida los datos recibidos
    $validated = $request->validate([
        '*.id' => 'required|integer',
        '*.codigo' => 'required|string|unique:productos,codigo',
        '*.nombre' => 'required|string|max:255',
        '*.precio' => 'required|numeric',
        '*.categoria_id' => 'required|exists:categorias,id', // Asegura que la categoría exista
    ]);

    try {
        DB::beginTransaction();
        
        // 1. Eliminar todos los productos existentes antes de eliminar las categorías
        Producto::query()->delete(); // Elimina todos los productos
        
        // 2. Insertar los nuevos productos
        $productos = array_map(function($producto) {
            return [
                'id' => $producto['id'],
                'codigo' => $producto['codigo'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'categoria_id' => $producto['categoria_id'],
            ];
        }, $validated);

        Producto::insert($productos);
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Productos reemplazados completamente',
            'count' => count($productos)
        ], 201);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        return response()->json([
            'success' => false,
            'message' => 'Error al reemplazar productos',
            'error' => $e->getMessage()
        ], 500);
    }
}




    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
