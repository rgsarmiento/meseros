<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all();

        return view('categorias.index',
         compact('categorias'));
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
        
    }



    public function replaceAll(Request $request)
    {
        // Valida los datos recibidos
        $validated = $request->validate([
            '*.id' => 'required|integer',
            '*.nombre' => 'required|string|max:255',
            '*.seccion_preparacion' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            
            // 1. Eliminar todas las categorías existentes en lugar de truncar
            Categoria::query()->delete(); // Elimina todas las filas de la tabla categorias
            
            // 2. Insertar las nuevas categorías
            $categorias = array_map(function($categoria) {
                return [
                    'id' => $categoria['id'],
                    'nombre' => $categoria['nombre'],
                    'seccion_preparacion' => $categoria['seccion_preparacion'],
                ];
            }, $validated);

            Categoria::insert($categorias);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Categorías reemplazadas completamente',
                'count' => count($categorias)
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al reemplazar categorías',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
