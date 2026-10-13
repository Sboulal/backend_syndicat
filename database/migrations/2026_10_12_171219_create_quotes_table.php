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
    Schema::create('quotes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
        $table->string('description');
        $table->decimal('amount', 10, 2);
        $table->text('conditions')->nullable(); // شروط الدفع أو الأجل
        $table->enum('status', ['received', 'studying', 'accepted', 'rejected'])->default('received');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
