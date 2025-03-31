<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenDetalle extends Model
{

     // Relación inversa con la tabla ordens
     public function orden()
     {
         return $this->belongsTo(Orden::class);
     }

    // Permitir asignación masiva en estos campos
    protected $fillable = [
        'orden_id',            // Asegura que este campo esté aquí para la asignación masiva
        'codigo_producto',
        'nombre_producto',
        'categoria_id',
        'cantidad',
        'precio',
        'observacion',
    ];
}
