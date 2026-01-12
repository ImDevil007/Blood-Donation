<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipientsTable extends Migration
{
    public function up()
    {
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_code')->unique();
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->string('gender');
            $table->string('blood_group');
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('hospital_name')->nullable();
            $table->string('doctor_name');
            $table->date('admission_date')->nullable();
            $table->date('blood_required_date')->nullable();
            $table->integer('blood_quantity_required')->nullable();
            $table->enum('request_status', ['pending', 'accepted', 'fulfilled', 'rejected'])->default('pending');
            $table->text('diagnosis');
            $table->text('notes')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipients');
    }
}
