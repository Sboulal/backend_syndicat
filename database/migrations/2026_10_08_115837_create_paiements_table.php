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
    Schema::create('paiements', function (Blueprint $table) {
        $table->id();
        $table->decimal('montant', 8, 2); // ex: 250.00 DH
        $table->date('mois_concerne'); // ex: 2026-10-01 (Ina chhar khalas)
        $table->date('date_paiement')->nullable();
        $table->enum('status', ['en_attente', 'valide', 'rejete'])->default('en_attente');
        $table->string('preuve_image')->nullable(); // Tswira dyal reçu (Wafacash, Virement)
        
        // Relation m3a appartement
        $table->foreignId('appartement_id')->constrained('appartements')->onDelete('cascade');
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
