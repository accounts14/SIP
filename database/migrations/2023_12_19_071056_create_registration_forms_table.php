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
        Schema::create('registration_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->string('ta', 10);
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('logo', 250)->nullable();
            $table->string('banner', 250)->nullable(); // banner / kop sekolah
            $table->integer('quota', false, true)->default(0); // kuota siswa yg diterima
            $table->integer('registration_fee', false, true)->default(0);
            $table->json('registration_field');
            $table->enum('status', ['0','1'])->default('0'); // 0 = tutup, 1 = buka
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_forms');
    }
};
