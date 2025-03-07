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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('clientName');
            $table->string('clientUsername');
            $table->string('source')->nullable();
            $table->string('clientPhone')->nullable();
            $table->string('clientMobile')->nullable();
            $table->string('clientEmail')->nullable();
            $table->string('meeting')->nullable();
            $table->string('clientCountry')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('clients');
    }
};
