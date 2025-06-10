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
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string('nomComplet', 100);
            $table->string('telephone', 20)->unique();
            $table->string('genre', 1);
            $table->integer('seuil');
            $table->integer('reste');
            $table->integer('pin');
            $table->integer('counter')->default(0);
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employers');
    }
};
