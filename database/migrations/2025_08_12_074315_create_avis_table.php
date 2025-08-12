<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->unsignedTinyInteger('nombre_etoile')->checkBetween(1,5);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('avis');
    }
};
