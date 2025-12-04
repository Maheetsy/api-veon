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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('companyName');
            $table->string('contactName');
            $table->string('email');
            $table->string('phoneNumber');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('postalCode');
            $table->string('country');
            $table->foreignId('userId')->nullable(); // Relación con usuario que lo creó
            $table->timestamps(); // created_at y updated_at
            
            // Índices para búsquedas rápidas
            $table->index('companyName');
            $table->index('email');
            $table->index('userId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};