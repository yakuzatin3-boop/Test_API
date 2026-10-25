<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')
                ->index()
                ->constrained('carts')
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->index()
                ->constrained('products')
                ->cascadeOnDelete();
            $table->foreignId('variant_id')
                ->nullable()
                ->index()
                ->constrained('product_variants')
                ->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });

        DB::statement(
            'CREATE UNIQUE INDEX cart_items_cart_product_variant_unique
             ON cart_items (cart_id, product_id, variant_id)
             WHERE variant_id IS NOT NULL'
        );

        DB::statement(
            'CREATE UNIQUE INDEX cart_items_cart_product_null_variant_unique
             ON cart_items (cart_id, product_id)
             WHERE variant_id IS NULL'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
