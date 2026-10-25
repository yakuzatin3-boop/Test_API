<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->index()
                ->constrained('orders')
                ->cascadeOnDelete();
            $table->string('tracking_number', 100)->nullable();
            $table->string('carrier', 100)->nullable();
            $table->jsonb('shipping_address')->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
