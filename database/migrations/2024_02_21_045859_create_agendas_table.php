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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->timestamp('event_date');
            $table->string('activity', 150);
            $table->string('slug', 160);
            $table->string('description', 250);
            $table->tinyInteger('duration')->default(2); // in hour
            $table->enum('repeat', [0,1,2,3,4])->default('0');
            // 0: no, 1: daily, 2: weekely, 3: monthly, 4: yearly
            $table->boolean('status')->default(false); // aktif or nonaktif
            $table->boolean('for_member')->default(false); // can be read by anyone/member
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
        Schema::dropIfExists('agendas');
    }
};
