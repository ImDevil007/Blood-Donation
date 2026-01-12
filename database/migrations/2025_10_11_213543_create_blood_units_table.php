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
        Schema::create('blood_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_id')->unique();
            $table->foreignId('donor_id')->constrained('donors')->onDelete('cascade');
            $table->string('blood_group')->nullable();
            $table->string('blood_type')->nullable();
            $table->date('collection_date');
            $table->date('expiry_date');
            $table->decimal('volume', 5, 2)->nullable();
            $table->string('storage_location')->nullable();
            $table->decimal('temperature', 4, 2)->nullable();
            $table->decimal('hemoglobin_level', 4, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_used')->default(false);
            $table->date('used_date')->nullable();
            $table->string('used_for')->nullable();
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
        Schema::dropIfExists('blood_units');
    }
};
