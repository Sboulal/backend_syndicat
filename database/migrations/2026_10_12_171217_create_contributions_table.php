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
    Schema::create('contributions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lot_id')->constrained('lots')->cascadeOnDelete();
        $table->string('title'); // مثال: واجب شهر يونيو 2026
        $table->decimal('amount_due', 10, 2); // شحال خاصو يخلص
        $table->date('due_date'); // آخر أجل للأداء
        $table->enum('status', ['paid', 'partial', 'unpaid', 'late'])->default('unpaid');
        $table->timestamps();
        $table->softDeletes();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
