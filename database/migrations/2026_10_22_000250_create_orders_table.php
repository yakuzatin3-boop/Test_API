<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->index()
                ->constrained('customers')
                ->restrictOnDelete();
            $table->string('order_number', 50)->unique();
            $table->string('status', 30)->default('pending');
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('note')->nullable();
            $table->jsonb('shipping_address')->nullable();
            $table->jsonb('billing_address')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('placed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
