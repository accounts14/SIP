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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200);
            $table->string('nik', 20);
            $table->string('nip', 20)->nullable();
            $table->string('nuptk', 20)->nullable();
            $table->string('tempat_lahir', 150)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jk', ['','Laki-laki','Perempuan'])->default('');
            $table->enum('agama', ['','Islam','Kristen','Katholik','Hindu','Budha','Konghucu'])->default('');
            $table->string('alamat', 250)->nullable();
            $table->string('no_hp', 16);
            $table->string('email', 50);
            $table->enum('kewarganegaraan', ['WNI','WNA'])->default('WNI');
            $table->string('jurusan', 250)->nullable();
            $table->string('keahlian', 250)->nullable();
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
        Schema::dropIfExists('teachers');
    }
};
