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
        Schema::create('product_variant_sizes', function (Blueprint $table) {
            $table->id();
            $table->integer('brand_id');
            $table->integer('product_variant_id');
            $table->string('name', 500)->nullable();
            $table->integer('price')->nullable();
            $table->integer('stock')->nullable();
            $table->integer('cost')->nullable();
            $table->string('cost_description', 500)->nullable();
            $table->integer('discount')->nullable();
            $table->boolean('is_precentage')->default(true);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('modified_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('modified_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_sizes');
    }
};
