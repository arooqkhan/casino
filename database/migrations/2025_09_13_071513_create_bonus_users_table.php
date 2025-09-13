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
        Schema::create('bonus_users', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('bonus_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('time')->nullable();
            $table->timestamps();

            // Foreign keys (agar aapke paas bonuses aur users table hain)
            $table->foreign('bonus_id')->references('id')->on('bonuses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_users');
    }
};
