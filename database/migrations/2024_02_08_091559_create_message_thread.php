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
        Schema::create('message_threads', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->unsignedBigInteger('initiator');
            $table->unsignedBigInteger('recipient');
            $table->timestamps();

            $table->foreign('initiator')->references('id')->on('users');
            $table->foreign('recipient')->references('id')->on('users');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('thread_id')->after('id');

            $table->foreign('thread_id')->references('id')->on('message_threads')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['thread_id']);

            $table->dropColumn('thread_id');
        });

        Schema::table('message_threads', function (Blueprint $table) {
            $table->dropForeign(['initiator']);
            $table->dropForeign(['recipient']);
        });

        Schema::dropIfExists('message_threads');
    }
};
