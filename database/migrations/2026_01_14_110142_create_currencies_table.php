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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // XOF, EUR, USD, etc.
            $table->string('symbol', 10); // FCFA, €, $, etc.
            $table->string('name'); // Franc CFA, Euro, Dollar US
            $table->decimal('exchange_rate', 15, 8)->default(1); // Taux par rapport à la devise de base (XOF)
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('preferred_currency_id')->nullable()->constrained('currencies')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('preferred_currency_id');
        });
    }
};
