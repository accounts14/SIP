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
        Schema::create('user_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16);
            $table->string('nama', 200);
            $table->string('nisn', 50);
            $table->string('tempat_lahir', 150);
            $table->date('tanggal_lahir');
            $table->enum('jk', ['','Laki-laki','Perempuan'])->default('');
            $table->enum('agama', ['','Islam','Kristen','Katholik','Hindu','Budha','Konghucu'])->default('');
            $table->string('alamat', 250)->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('dusun', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kabupaten', 100)->nullable(); // kab / kota
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('jenis_tinggal', 50)->nullable();
            $table->string('alat_transportasi', 25)->nullable();
            $table->string('no_telepon', 16)->nullable();
            $table->string('no_hp', 16);
            $table->string('email', 50);
            $table->enum('kewarganegaraan', ['WNI','WNA'])->default('WNI');
            // data ayah
            $table->string('nik_ayah', 16)->nullable();
            $table->string('nama_ayah', 200)->nullable();
            $table->string('tahun_lahir_ayah', 5)->nullable();
            $table->string('jenjang_pendidikan_ayah', 50)->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->double('penghasilan_ayah')->nullable();
            // data ibu
            $table->string('nik_ibu', 16)->nullable();
            $table->string('nama_ibu', 200)->nullable();
            $table->string('tahun_lahir_ibu', 5)->nullable();
            $table->string('jenjang_pendidikan_ibu', 50)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->double('penghasilan_ibu')->nullable();
            // data wali
            $table->string('nik_wali', 16)->nullable();
            $table->string('nama_wali', 200)->nullable();
            $table->string('tahun_lahir_wali', 5)->nullable();
            $table->string('jenjang_pendidikan_wali', 50)->nullable();
            $table->string('pekerjaan_wali', 100)->nullable();
            $table->double('penghasilan_wali')->nullable();
            
            $table->string('kebutuhan_khusus', 100)->nullable();
            $table->string('sekolah_asal', 150)->nullable();
            $table->integer('anak_ke', false, true)->nullable();
            $table->double('lintang')->nullable();
            $table->double('bujur')->nullable();
            $table->string('no_kk', 20)->nullable();
            $table->double('berat_badan')->nullable(); // cm
            $table->double('tinggi_badan')->nullable(); // cm
            $table->integer('jml_saudara_kandung', false, true)->nullable();
            $table->double('jarak_rumah_kesekolah')->nullable(); // km

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_candidates');
    }
};
