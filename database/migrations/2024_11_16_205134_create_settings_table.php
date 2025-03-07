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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Store the name of the site or company
            $table->string('logo')->nullable(); // Store the logo file path (nullable in case no logo is set)
            $table->string('email')->nullable(); // Email address
            $table->string('phone')->nullable(); // Contact phone number
            $table->string('copyright')->nullable(); // Copyright text
            $table->text('other_info')->nullable(); // Any other information
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
