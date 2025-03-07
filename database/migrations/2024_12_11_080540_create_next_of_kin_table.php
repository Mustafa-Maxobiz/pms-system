<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNextOfKinTable extends Migration
{
    public function up()
    {
        Schema::create('next_of_kin', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->string('name'); // Name of the next of kin
            $table->string('relation'); // Relation to the user
            $table->string('contact'); // Contact information (e.g., phone number)
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes();
            // Foreign key constraint: references 'id' column of 'users' table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('next_of_kin');
    }
}
