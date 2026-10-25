<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->index()
                ->constrained('orders')
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->nullable()
                ->index()
                ->constrained('products')
                ->nullOnDelete();
            $table->foreignId('variant_id')
                ->nullable()
                ->index()
                ->constrained('product_variants')
                ->nullOnDelete();
            $table->string('product_name');
            $table->string('sku', 100)->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
