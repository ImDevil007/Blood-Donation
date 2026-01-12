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
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->string('donor_id')->unique();
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone', 15);
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->unsignedBigInteger('blood_group')->nullable();
            $table->unsignedBigInteger('gender')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->text('medical_history')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medications')->nullable();
            $table->date('last_donation_date')->nullable();
            $table->integer('total_donations')->default(0);
            $table->boolean('is_eligible')->default(true);
            $table->text('eligibility_reason')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('id_proof_path')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 15)->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('blood_group')->references('id')->on('lovs')->onDelete('set null');
            $table->foreign('gender')->references('id')->on('lovs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
