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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('projectId');
            $table->string('projectName');
            $table->string('source')->nullable();
            $table->string('clientname')->nullable();
            $table->string('url')->nullable();
            $table->string('externalstatus')->nullable();
            $table->string('totalAmount')->nullable();
            $table->string('startDate')->nullable();
            $table->string('targetDate')->nullable();
            $table->string('completionDate')->nullable();
            $table->string('projectalerts')->nullable();
            $table->string('finalfeedback')->nullable();
            $table->string('author');            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
