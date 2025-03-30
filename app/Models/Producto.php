<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo', 'nombre', 'precio', 'imagen', 'categoria_id',
    ];

    // Relación: Un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
