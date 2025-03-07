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
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->string('title');               // Title of the knowledge base article
            $table->text('content');               // Content of the knowledge base article
            $table->json('attachments')->nullable(); // Attachments, storing file paths as JSON
            $table->unsignedBigInteger('department_id')->nullable();
            $table->json('tags')->nullable();
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
        Schema::dropIfExists('knowledge_bases');
    }
};
