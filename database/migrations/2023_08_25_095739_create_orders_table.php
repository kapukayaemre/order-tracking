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
            $table->foreignId("product_id")->constrained()->cascadeOnDelete();
            $table->foreignId("discount_id")->nullable()->constrained()->cascadeOnDelete();
            $table->string("order_code");
            $table->bigInteger("quantity");
            $table->integer("free_product_quantity")->nullable();
            $table->text("free_product_list")->nullable();
            $table->double("discount_difference", 8, 2)->nullable();
            $table->double("amount", 8, 2);
            $table->double("free_product_list_amount", 8, 2)->nullable();
            $table->double("shipping_price")->nullable()->default(75.00);
            $table->text("description")->nullable();
            $table->enum("status", ["active", "passive"])->default("active");
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
