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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId("author_id")->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId("category_id")->nullable()->constrained()->cascadeOnDelete();
            $table->double("min_amount", 8, 2)->nullable();
            $table->integer("discount_rate")->nullable();
            $table->integer("min_buy_count")->nullable();
            $table->integer("free_count")->nullable();
            $table->enum("status", ["active", "passive"])->default("active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
