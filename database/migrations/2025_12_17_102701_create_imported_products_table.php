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
        Schema::create('imported_products', function (Blueprint $table) {
            $table->id();
            $table->string('source_url')->unique();
            $table->string('title');
            $table->decimal('price_min', 10, 2)->nullable();
            $table->decimal('price_max', 10, 2)->nullable();
            $table->integer('moq')->nullable();
            $table->json('images'); // array d'URLs
            $table->text('description')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_url')->nullable();
            $table->json('variants')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imported_products');
    }
};
