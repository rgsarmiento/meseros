<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrdenDetalle extends Model
{

     // Relación inversa con la tabla ordens
     public function orden()
     {
         return $this->belongsTo(Orden::class);
     }

     protected static function boot()
    {
        parent::boot();

        // Generar la llave única automáticamente antes de crear el registro
        static::creating(function ($orden) {
            $orden->llave = Str::uuid(); // Usamos UUID como llave única
        });
    }

    // Permitir asignación masiva en estos campos
    protected $fillable = [
        'llave',
        'orden_id',            // Asegura que este campo esté aquí para la asignación masiva
        'codigo_producto',
        'nombre_producto',
        'categoria_id',
        'cantidad',
        'precio',
        'observacion',
    ];
}
