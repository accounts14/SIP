<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'agama',
        'alamat',
        'rt',
        'rw',
        'dusun',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'jenis_tinggal',
        'alat_transportasi',
        'no_telepon',
        'no_hp',
        'email',
        'nik_ayah',
        'nama_ayah',
        'tahun_lahir_ayah',
        'jenjang_pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'nik_ibu',
        'nama_ibu',
        'tahun_lahir_ibu',
        'jenjang_pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'nik_wali',
        'nama_wali',
        'tahun_lahir_wali',
        'jenjang_pendidikan_wali',
        'pekerjaan_wali',
        'penghasilan_wali',
        'kebutuhan_khusus',
        'sekolah_asal',
        'anak_ke',
        'lintang',
        'bujur',
        'no_kk',
        'berat_badan',
        'tinggi_badan',
        'jml_saudara_kandung',
        'jarak_rumah_kesekolah',
    ];
    
    public function registration() {
        return $this->hasMany(StudentRegistration::class, 'student_id');
    }
    
    public function member() {
        return $this->hasMany(UserMember::class, 'student_id');
    }
}
