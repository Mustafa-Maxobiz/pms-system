<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null'); // Foreign key to projects table
            $table->string('task_name'); // Name of the task
            $table->string('task_type')->nullable(); // Type of the task
            $table->string('task_value')->nullable(); // Value/estimate of the task
            $table->date('start_date')->nullable(); // Start date of the task
            $table->date('end_date')->nullable(); // End date of the task
            $table->string('task_status')->nullable(); // Status of the task
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null'); // Foreign key to departments table
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null'); // Foreign key to teams table
            $table->foreignId('assign_id')->nullable()->constrained('users')->onDelete('set null'); // Foreign key to assigned user
            $table->foreignId('finalized')->nullable()->constrained('users')->onDelete('set null'); // Foreign key to finalized by user
            $table->string('csr')->nullable(); // Customer Service Representative associated with the task
            $table->string('task_priority')->nullable(); // Priority of the task
            $table->string('personal_email')->nullable(); // Personal email associated with the task
            $table->text('task_description')->nullable(); // Description of the task
            $table->json('attachments')->nullable(); // Attachments, storing file paths as JSON
            $table->foreignId('author')->constrained('users')->onDelete('cascade'); // Foreign key to author (user who created the task)
            $table->timestamps();
            $table->softDeletes();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
