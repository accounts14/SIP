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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('brand_id');
            $table->string('transaction_code', 500)->unique()->nullable();
            $table->date('transaction_date');
            $table->integer('customer_id')->nullable();
            $table->integer('created_customer_id')->nullable();
            $table->integer('address')->nullable();
            $table->integer('grand_total_price')->nullable();
            $table->integer('grand_total_cost')->nullable();
            $table->integer('actual_price')->nullable();
            $table->integer('transaction_status_id');
            $table->string('transaction_description', 500)->nullable();
            
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
        Schema::dropIfExists('transactions');
    }
};
