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
            $table->string('otp')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('password');
            $table->tinyInteger('status')->default(1)->comment('0 => inactive, 1 => active');
            $table->rememberToken();
            $table->timestamps();
            $table->integer('matches')->default(0);
            $table->integer('total_wager')->default(0);
            $table->integer('largest_wager')->default(0);
            $table->integer('win')->default(0);
            $table->integer('total_earning')->default(0);
            $table->integer('hold_amount')->default(0);
            $table->integer('balance')->default(0);
            $table->softDeletes();
            $table->text('firebase_token')->nullable();
            $table->string('code')->nullable();
            $table->string('invited_code')->nullable();

            // Extra fields you requested
            $table->boolean('verification_status')->default(false);
            $table->boolean('bonus_status')->default(false);
            $table->boolean('kyc_status')->default(false);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
