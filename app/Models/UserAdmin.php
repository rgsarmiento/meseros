<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserAdmin extends Model
{
    // Si la tabla no se llama 'user_admins', especificamos el nombre de la tabla
    protected $table = 'users';

    // Especificamos qué campos pueden ser asignados masivamente
    protected $fillable = ['id', 'name', 'email', 'password'];

    // Desactivamos la protección de timestamps, si no necesitas los campos `created_at` y `updated_at`
    public $timestamps = false;  // Si quieres que Laravel no maneje los timestamps

    // Método para crear o actualizar usuarios sin autenticación
    public static function createOrUpdateUser(array $data)
    {
        // Verificamos si el usuario ya existe por el id
        $user = self::find($data['id']);

        if ($user) {
            // Si el usuario ya existe, actualizamos
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),  // Ciframos la contraseña
            ]);
        } else {
            // Si el usuario no existe, lo creamos
            self::create([
                'id' => $data['id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
    }
}
