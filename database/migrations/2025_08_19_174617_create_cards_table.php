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
         Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_holder_name');
            $table->string('card_number');
            $table->string('expiry_date');
            $table->string('ccv_code');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('province');
            $table->string('postal_code');
            $table->string('city');
            $table->string('country');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
