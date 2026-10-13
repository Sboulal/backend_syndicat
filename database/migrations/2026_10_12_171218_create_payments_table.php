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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('contribution_id')->constrained('contributions')->cascadeOnDelete();
        $table->decimal('amount_paid', 10, 2); // شحال خلص فعليا
        $table->date('payment_date');
        $table->enum('payment_method', ['virement', 'especes', 'cheque']);
        $table->string('reference')->nullable(); // رقم الشيك أو الفيرمون
        $table->string('receipt_number')->unique(); // رقم التوصيل (ضروري يكون unique)
        $table->string('proof_path')->nullable(); // تصويرة ديال التوصيل (URL)
        $table->enum('status', ['pending_validation', 'validated', 'rejected'])->default('pending_validation');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
