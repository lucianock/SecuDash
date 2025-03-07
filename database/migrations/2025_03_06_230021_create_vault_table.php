<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('vault', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nombre de la cuenta
        $table->string('type'); // cPanel, FTP, WHM
        $table->string('host'); // Dirección del servidor
        $table->string('username');
        $table->string('password'); // Encriptado luego
        $table->text('notes')->nullable(); // Notas adicionales
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con usuario
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vault');
    }
};
