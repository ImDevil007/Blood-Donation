<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLovCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lov_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique('name');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('lov_category');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
