<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('campaign_subscribe', function (Blueprint $table) {
            $table->enum('result', ['pending', 'win', 'loss', 'draw'])
                ->default('pending')
                ->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('campaign_subscribe', function (Blueprint $table) {
            $table->dropColumn('result');
        });
    }
};
