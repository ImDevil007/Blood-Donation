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
        Schema::create('blood_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_id')->unique();
            $table->foreignId('blood_unit_id')->constrained('blood_units')->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained('users');
            $table->date('test_date');
            $table->string('hiv_result')->nullable(); // negative, positive, pending
            $table->string('hepatitis_b_result')->nullable();
            $table->string('hepatitis_c_result')->nullable();
            $table->string('syphilis_result')->nullable();
            $table->string('malaria_result')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('overall_status')->default('pending'); // pending, passed, failed, quarantined
            $table->text('test_notes')->nullable();
            $table->string('lab_reference')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_tests');
    }
};
