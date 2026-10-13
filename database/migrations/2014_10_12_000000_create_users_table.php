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
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('first_name'); // زيدي هادي
        $table->string('last_name');  // زيدي هادي
        $table->string('email')->unique();
        $table->string('phone')->nullable();
        $table->enum('role', ['admin', 'syndic', 'adjoint', 'owner', 'tenant'])->default('owner');
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
        $table->softDeletes(); // مهمة بزاف باش نحافظو على الأرشيف
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
