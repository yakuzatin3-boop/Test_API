<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->nullable()
                ->index()
                ->constrained('categories')
                ->nullOnDelete();
            $table->foreignId('brand_id')
                ->nullable()
                ->index()
                ->constrained('brands')
                ->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 100)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('status', 30)->default('active');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_flash_sale')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('status');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
