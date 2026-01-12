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
        Schema::create('donation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->onDelete('cascade');
            $table->datetime('donation_date');
            $table->string('donation_type')->nullable();
            $table->decimal('blood_volume', 5, 2)->nullable();
            $table->string('collection_location')->nullable();
            $table->string('camp_id')->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users');
            $table->json('test_results')->nullable();
            $table->decimal('hemoglobin_level', 4, 2)->nullable();
            $table->string('blood_pressure')->nullable();
            $table->integer('pulse_rate')->nullable();
            $table->decimal('temperature', 4, 2)->nullable();
            $table->decimal('weight_at_donation', 5, 2)->nullable();
            $table->enum('donation_status', ['successful', 'rejected', 'pending'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('donation_histories');
    }
};
