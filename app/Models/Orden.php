<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Orden extends Model
{
    // Especificar el nombre de la tabla si no sigue la convención
    protected $table = 'ordens'; // Cambia 'ordenes' por el nombre de tu tabla

    // Relación con la tabla orden_detalles
    public function detalles()
    {
        return $this->hasMany(OrdenDetalle::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Generar la llave única automáticamente antes de crear el registro
        static::creating(function ($orden) {
            $orden->llave = Str::uuid(); // Usamos UUID como llave única
        });
    }

    protected $fillable = [
        'llave', 'fechahora', 'usuario_id', 'mesa_id', 'total', 'estado'
    ];
}
