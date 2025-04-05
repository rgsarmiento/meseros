<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            '*.nombre' => 'required|string|max:255',
            '*.capacidad' => 'required|integer',
            '*.estado' => 'required|in:libre,ocupada,reservada,limpieza',
            '*.zona' => 'required|string|max:255',
            '*.hora_estado' => 'nullable|date_format:Y-m-d H:i:s', // Si el campo hora_estado es opcional
        ]);

        try {
            DB::beginTransaction();

            // 1. Prepara las mesas para la inserción o actualización
            $mesas = array_map(function ($mesa) {
                return [
                    'id' => $mesa['id'],
                    'nombre' => $mesa['nombre'],
                    'capacidad' => $mesa['capacidad'],
                    'estado' => $mesa['estado'],
                    'hora_estado' => $mesa['hora_estado'] ?? null,  // Si hora_estado es nulo, se asigna null
                    'zona' => $mesa['zona'],
                    'updated_at' => now(),  // Actualiza la fecha de actualización
                ];
            }, $validated);

            // 2. Usar upsert para insertar o actualizar las mesas según el id
            // 'id' es la clave única que definirá si la mesa ya existe
            Mesa::upsert($mesas, ['id'], ['nombre', 'capacidad', 'zona', 'updated_at']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mesas reemplazadas correctamente',
                'count' => count($mesas)
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al reemplazar mesas',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function liberarMesas()
    {
        try {
            DB::beginTransaction();

            // 1. Actualizar el estado de todas las mesas a 'libre'
            Mesa::query()->update([
                'estado' => 'libre',  // Cambia el estado a 'libre'
                'hora_estado' => null, // Deja la hora de estado en null
                'updated_at' => now(), // Actualiza la fecha de actualización
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Todas las mesas han sido liberadas.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al liberar las mesas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Mesa $mesa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mesa $mesa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mesa $mesa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mesa $mesa)
    {
        //
    }
}
