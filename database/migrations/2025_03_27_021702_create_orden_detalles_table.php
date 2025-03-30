<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orden_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orden_id')->constrained('ordens')->onDelete('cascade');
            $table->string('codigo_producto');
            $table->string('nombre_producto');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_detalles');
    }
};
