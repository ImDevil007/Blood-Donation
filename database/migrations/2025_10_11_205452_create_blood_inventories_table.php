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
        Schema::create('blood_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_id')->unique();
            $table->unsignedBigInteger('blood_group')->nullable();;
            $table->unsignedBigInteger('blood_type')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('units');
            $table->date('collection_date');
            $table->date('expiry_date');
            $table->string('storage_location')->nullable();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('blood_group')->references('id')->on('lovs')->onDelete('set null');
            $table->foreign('blood_type')->references('id')->on('lovs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_inventories');
    }
};
