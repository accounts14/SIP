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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('caption', 200);
            $table->string('description', 500);
            $table->string('path', 200);
            // $table->unsignedBigInteger('imageable_id')->default(0);
            // $table->string('imageable_type', 100)->nullable();
            $table->nullableMorphs('imageable');
            $table->unsignedBigInteger('school_id');
            $table->timestamps();
            
            $table->foreign('school_id')->references('id')->on('schools');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
