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
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            // CNIC / Passport / License / etc.
            $table->enum('document_type', [
                'cnic_front',
                'cnic_back',
                'passport',
                'driving_license',
                'utility_bill',
                'other'
            ])->default('other');

            $table->string('document_number')->nullable(); // e.g. CNIC no, Passport no
            $table->string('file_path'); // Path to uploaded document
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('reject_reason')->nullable();

            $table->timestamps();

            // foreign key → users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};
