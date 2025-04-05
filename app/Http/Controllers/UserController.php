<?php

namespace App\Http\Controllers;

use App\Models\UserAdmin;
use Illuminate\Http\Request;


class UserController extends Controller
{
    //
    public function replaceAll(Request $request)
    {
        // Validar los datos recibidos (debería incluir 'id', 'name', 'email', 'password')
        $validated = $request->validate([
            '*.id' => 'required|integer',  // Asegura que se pase el ID
            '*.name' => 'required|string|max:255', // Nombre del usuario
            '*.email' => 'required', // Validar que el correo sea único
            '*.password' => 'required|string|min:4', // Asegura que el password esté presente
        ]);

        try {
            // Usar el modelo UserAdmin para insertar o actualizar usuarios
            foreach ($validated as $userData) {
                UserAdmin::createOrUpdateUser($userData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Usuarios reemplazados correctamente',
                'count' => count($validated)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reemplazar usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}
