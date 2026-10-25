<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->index()
                ->constrained('products')
                ->cascadeOnDelete();
            $table->foreignId('variant_id')
                ->nullable()
                ->index()
                ->constrained('product_variants')
                ->nullOnDelete();
            $table->integer('quantity')->default(0);
            $table->string('warehouse_location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
