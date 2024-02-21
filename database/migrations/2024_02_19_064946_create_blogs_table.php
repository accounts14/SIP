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
        Schema::create('blog', function (Blueprint $table) {
            $table->id();
            $table->string('title', 250);
            $table->string('slug', 260);
            $table->text('content')->nullable();
            $table->boolean('for_member')->default(false); // can be read by anyone/member
            $table->enum('category', [0,1,2,3,4])->default(1);
            // 0: uncategories, 1: information, 2: announcement, 3: news, 4: invitation
            $table->string('tags', 500)->nullable();
            $table->unsignedBigInteger('school_id'); // owner of post
            $table->unsignedBigInteger('publisher'); // publisher of post
            $table->timestamp('published_at')->nullable(); // null is draf
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('publisher')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
};
