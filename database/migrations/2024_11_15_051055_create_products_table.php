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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Product name
            $table->string('category'); // Product category
            $table->text('description')->nullable(); // Optional description
            $table->decimal('price', 10, 2); // Price with up to 10 digits, 2 after the decimal
            $table->integer('stock'); // Stock quantity
            $table->string('image')->nullable(); // Path to the image
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
