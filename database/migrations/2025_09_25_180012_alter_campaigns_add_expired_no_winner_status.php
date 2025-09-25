<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->enum('status', ['active', 'upcoming', 'expired', 'expired_no_winner'])
                ->default('upcoming')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->enum('status', ['active', 'upcoming', 'expired'])
                ->default('upcoming')
                ->change();
        });
    }
};
