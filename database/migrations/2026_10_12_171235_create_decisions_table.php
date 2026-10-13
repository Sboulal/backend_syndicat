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
    Schema::create('decisions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('meeting_id')->constrained('meetings')->cascadeOnDelete();
        $table->text('description');
        $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // المكلف بالتنفيذ
        $table->date('deadline_date')->nullable();
        $table->enum('status', ['to_do', 'in_progress', 'done'])->default('to_do');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
