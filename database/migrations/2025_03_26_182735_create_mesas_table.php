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
        Schema::create('mesas', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('nombre');
            $table->integer('capacidad');
            $table->enum('estado', ['libre', 'ocupada', 'reservada', 'limpieza'])->default('libre');
            $table->timestamp('hora_estado')->nullable();
            $table->string('zona');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
