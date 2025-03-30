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
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->string('llave')->unique();
            $table->timestamp('fechahora');
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('mesa_id')->constrained('mesas');
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['pendiente', 'actualizar', 'preparando', 'terminado', 'procesado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordens');
    }
};
