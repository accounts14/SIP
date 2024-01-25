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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('achieved_from', 200);
            $table->string('description', 250)->nullable();
            $table->date('achieved_at');
            $table->enum('achieved_by', [1,2,3]); // 1 = school, 2 = teacher, 3 = student
            $table->enum('level', [0,1,2,3,4,5])->default(0);
            // 0 = no level
            // 1 = internal level
            // 2 = regional level
            // 3 = provincial level
            // 4 = national level
            // 5 = international level
            $table->unsignedBigInteger('school_id');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('school_id')->references('id')->on('schools');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
