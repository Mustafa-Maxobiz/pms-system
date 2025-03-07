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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null'); // Foreign key to projects table, with nullable and set null on delete
            $table->string('title'); // Title of the payment
            $table->string('description')->nullable(); // Description of the payment, nullable
            $table->text('task_ids')->nullable();
            $table->decimal('selected_task_value', 10, 2)->nullable(); // Decimal for selected task value, nullable
            $table->decimal('discount', 10, 2)->nullable(); // Decimal for discount, nullable
            $table->decimal('gst', 10, 2)->nullable(); // Decimal for GST, nullable
            $table->decimal('payed_amount', 10, 2)->nullable(); // Decimal for paid amount, nullable
            $table->decimal('remaining_payment', 10, 2)->nullable(); // Decimal for remaining payment, nullable
            $table->foreignId('author')->constrained('users')->onDelete('cascade'); // Foreign key to users table, with cascading delete
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes();
        });               
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
