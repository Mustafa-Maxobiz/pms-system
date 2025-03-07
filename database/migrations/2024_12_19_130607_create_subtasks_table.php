<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubtasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();  // Primary key, auto-incremented
            $table->string('name');  // Subtask name
            $table->text('value')->nullable();  // Optional description for the subtask
            $table->foreignId('task_id')->constrained()->onDelete('cascade'); // Foreign key to tasks table
            $table->timestamps();  // Created at and updated at
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subtasks');
    }
}
