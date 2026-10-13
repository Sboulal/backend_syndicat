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
    Schema::create('claims', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // مول الشكاية
        $table->foreignId('lot_id')->nullable()->constrained('lots')->nullOnDelete();
        $table->foreignId('intervention_id')->nullable()->constrained('interventions')->nullOnDelete(); // للربط مع إصلاح
        $table->string('subject');
        $table->text('description');
        $table->enum('priority', ['low', 'normal', 'urgent'])->default('normal');
        $table->enum('status', ['new', 'in_progress', 'waiting', 'resolved'])->default('new');
        $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // شكون مكلف بيها
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
