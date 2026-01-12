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
        Schema::create('blood_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_id')->unique();
            $table->string('source_bank');
            $table->string('destination_bank');
            $table->unsignedBigInteger('blood_group');
            $table->unsignedBigInteger('blood_type')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('units');
            $table->decimal('temperature', 5, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('blood_group')->references('id')->on('lovs')->onDelete('restrict');
            $table->foreign('blood_type')->references('id')->on('lovs')->onDelete('set null');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_transfers');
    }
};
