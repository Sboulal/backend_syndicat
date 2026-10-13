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
    Schema::create('interventions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('equipment_id')->nullable()->constrained('equipments')->nullOnDelete();
        $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
        $table->string('subject'); // موضوع الإصلاح
        $table->dateTime('scheduled_date')->nullable();
        $table->dateTime('realized_date')->nullable();
        $table->enum('priority', ['low', 'normal', 'urgent'])->default('normal');
        $table->enum('status', ['to_plan', 'planned', 'in_progress', 'done'])->default('to_plan');
        $table->decimal('estimated_cost', 10, 2)->nullable();
        $table->decimal('real_cost', 10, 2)->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
