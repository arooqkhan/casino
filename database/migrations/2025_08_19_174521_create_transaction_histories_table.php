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
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['withdraw', 'deposit']);
            $table->double('amount', 8, 2);
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->integer('is_sent')->default(0);
            $table->string('trans_type')->nullable();
            $table->enum('payment_status', ['pending', 'approved'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_histories');
    }
};
