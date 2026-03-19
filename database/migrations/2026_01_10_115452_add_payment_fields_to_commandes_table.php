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
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('status', 50)->default('pending');
            $table->string('payment_method', 50)->nullable()->after('id');
            $table->string('payment_status', 50)->default('pending')->after('payment_method');
            $table->string('payment_id', 255)->nullable()->after('payment_status');
            $table->string('payment_email', 255)->nullable()->after('payment_id');
            $table->decimal('total_amount', 10, 2)->default(0.00)->after('payment_email');

            $table->string('delivery_method')->nullable()->after('type_id');
            $table->json('delivery_info')->nullable()->after('delivery_method');
            $table->decimal('shipping_fee', 10, 2)->nullable()->after('total_amount');
            $table->string('shipping_status')->nullable()->after('shipping_fee');
            $table->date('estimated_delivery')->nullable()->after('shipping_status');
            $table->text('observations')->nullable()->after('estimated_delivery');
            $table->string('shipping_payment_id')->nullable()->after('shipping_status');
            $table->timestamp('shipping_payment_date')->nullable()->after('shipping_payment_id');
            $table->string('shipping_payment_method')->nullable()->after('shipping_payment_date');
            $table->boolean('is_received')->default(false)->after('shipping_payment_date');
            $table->timestamp('received_at')->nullable()->after('is_received');
        });

        Schema::create('commander', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'payment_id',
                'payment_email',
                'total_amount',
                'status',
                'delivery_method',
                'delivery_info',
                'shipping_fee',
                'shipping_status',
                'estimated_delivery',
                'observations',
                'shipping_payment_id',
                'shipping_payment_date',
                'is_received',
                'received_at',
                'shipping_payment_method',
            ]);
        });
        Schema::table('commander', function (Blueprint $table) {
            $table->dropColumn([
                'unit_price',
                'total_price',
            ]);
        });


    }
};
