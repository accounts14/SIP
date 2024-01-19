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
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id'); // sekolah tujuan
            $table->unsignedBigInteger('student_id'); // siswa yg mendaftar
            $table->unsignedBigInteger('registration_form_id'); // form registrasi yg dibuka sekolah
            $table->enum('registered_by', ['1','2','9'])->default('9'); // 1 = sendiri, 2 = orang tua/wali, 9 = lewat sekolah
            $table->string('school_origin', 100); // sekolah asal
            $table->enum('status', ['0','1','2','3','4','9'])->default('0');
            // 0 = menunggu pembayaran, 1 = pendaftaran diproses, 2 = lolos berkas, 3 = tidak lolos, 4 = diterima, 9 = ditolak/batal
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('student_id')->references('id')->on('user_candidates');
            $table->foreign('registration_form_id')->references('id')->on('registration_forms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};
