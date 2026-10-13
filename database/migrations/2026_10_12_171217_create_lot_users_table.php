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
    Schema::create('lot_users', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lot_id')->constrained('lots')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->enum('relation_type', ['owner', 'tenant']); // مالك أو مكتري
        $table->date('start_date'); // تاريخ الدخول
        $table->date('end_date')->nullable(); // تاريخ الخروج (يلا كان باقي ساكن كتبقى خاوية)
        $table->timestamps();
        $table->softDeletes();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lot_users');
    }
};
