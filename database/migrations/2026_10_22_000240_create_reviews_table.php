<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->index()
                ->constrained('products')
                ->cascadeOnDelete();
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->cascadeOnDelete();
            $table->smallInteger('rating');
            $table->text('comment')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->unique(['customer_id', 'product_id']);
            $table->index('status');
        });

        // SQLite cannot add CHECK constraints after table creation.
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement(
                'ALTER TABLE reviews
                 ADD CONSTRAINT reviews_rating_check
                 CHECK (rating BETWEEN 1 AND 5)'
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
