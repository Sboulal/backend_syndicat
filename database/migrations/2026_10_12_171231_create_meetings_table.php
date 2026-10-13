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
    Schema::create('meetings', function (Blueprint $table) {
        $table->id();
        $table->dateTime('scheduled_date');
        $table->json('agenda')->nullable(); // جدول الأعمال
        $table->enum('status', ['planned', 'done', 'canceled'])->default('planned');
        $table->string('minutes_path')->nullable(); // محضر الاجتماع PV (URL)
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
