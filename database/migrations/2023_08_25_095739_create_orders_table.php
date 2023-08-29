<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->string("order_code");
            $table->bigInteger("product_count");
            $table->double("discount_amount", 8, 2)->nullable();
            $table->double("discounted_amount", 8, 2)->nullable();
            $table->double("total_amount", 8, 2);
            $table->double("shipping_price", 8, 2)->nullable();
            $table->integer("discount_rate")->nullable();
            $table->enum('status', ['active', 'passive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
