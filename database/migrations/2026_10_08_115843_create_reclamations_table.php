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
    Schema::create('reclamations', function (Blueprint $table) {
        $table->id();
        $table->string('titre');
        $table->text('description');
        $table->string('photo_url')->nullable();
        $table->enum('status', ['jdida', 'en_cours', 'resolue'])->default('jdida');
        
        // Chkon dar la réclamation
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
