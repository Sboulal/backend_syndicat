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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained('expense_categories')->cascadeOnDelete();
        $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
        $table->string('description');
        $table->decimal('amount', 10, 2);
        $table->date('expense_date'); // تاريخ الفاتورة
        $table->date('payment_date')->nullable(); // تاريخ الأداء (يلا تخلصات)
        $table->enum('payment_method', ['virement', 'especes', 'cheque'])->nullable();
        $table->enum('status', ['to_pay', 'partial', 'paid'])->default('to_pay');
        $table->string('invoice_path')->nullable(); // الفاتورة مسكانية (URL)
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
