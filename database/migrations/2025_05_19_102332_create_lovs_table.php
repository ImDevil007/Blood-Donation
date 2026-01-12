<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLovsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lovs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('remarks')->nullable();
            $table->foreignId('lov_category_id')->constrained('lov_categories', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('parent_id')->nullable();
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
        Schema::dropIfExists('lov');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
