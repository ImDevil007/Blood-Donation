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
        Schema::create('blood_collection_camps', function (Blueprint $table) {
            $table->id();
            $table->string('camp_id')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('target_donors')->default(0);
            $table->integer('actual_donors')->default(0);
            $table->integer('collected_units')->default(0);
            $table->string('organizer_name');
            $table->string('organizer_contact');
            $table->string('status')->default('scheduled'); // scheduled, ongoing, completed, cancelled
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
        Schema::dropIfExists('blood_collection_camps');
    }
};
