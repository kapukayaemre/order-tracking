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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained()->cascadeOnDelete();
            $table->foreignId("product_id")->constrained()->cascadeOnDelete();
            $table->foreignId('discount_id')->nullable()->constrained()->cascadeOnDelete();
            $table->bigInteger('quantity');
            $table->double('price', 8, 2);
            $table->unsignedBigInteger('free_product')->nullable();
            $table->text("address")->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
