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
        Schema::create('created_customer', function (Blueprint $table) {
            $table->id();
            $table->integer('brand_id');
            $table->string('name', 500);
            $table->string('email', 500)->nullable();
            $table->integer('phone')->nullable();
            $table->string('address', 500)->nullable();
            $table->json('social_media')->nullable();
            $table->string('description', 500)->nullable();

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
        Schema::dropIfExists('created_customer');
    }
};
