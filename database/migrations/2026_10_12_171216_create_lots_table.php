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
    Schema::create('lots', function (Blueprint $table) {
        $table->id();
        $table->foreignId('building_id')->nullable()->constrained()->nullOnDelete();
        $table->string('number'); // مثال: A01
        $table->integer('floor')->nullable(); // الطابق
        $table->enum('type', ['appartement', 'commerce', 'cave', 'garage'])->default('appartement');
        $table->decimal('quote_part', 8, 2)->default(0); // حصة الشقة لحساب المصاريف
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
