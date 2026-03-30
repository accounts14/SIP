<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentRegistrationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'school_id'            => $this->school_id,
            'student_id'           => $this->student_id,
            'registration_form_id' => $this->registration_form_id,
            'registered_by'        => $this->registered_by,
            'school_origin'        => $this->school_origin,
            'status'               => $this->status,
            'updated_at'           => $this->updated_at,

            'school' => $this->whenLoaded('school', fn() => [
                'name'          => $this->school->name,
                'type'          => $this->school->type,
                'accreditation' => $this->school->accreditation,
                'level'         => $this->school->level,
                'npsn'          => $this->school->npsn,
            ]),

            // Field lengkap untuk export Excel pimpinan
            'student' => $this->whenLoaded('student', fn() => [
                // Identitas
                'nama'                    => $this->student->nama,
                'nik'                     => $this->student->nik,
                'nisn'                    => $this->student->nisn,
                'tempat_lahir'            => $this->student->tempat_lahir,
                'tanggal_lahir'           => $this->student->tanggal_lahir,
                'jk'                      => $this->student->jk,
                'agama'                   => $this->student->agama,
                'kewarganegaraan'         => $this->student->kewarganegaraan,
                // Kontak
                'no_hp'                   => $this->student->no_hp,
                'no_telepon'              => $this->student->no_telepon,
                'email'                   => $this->student->email,
                // Alamat
                'alamat'                  => $this->student->alamat,
                'rt'                      => $this->student->rt,
                'rw'                      => $this->student->rw,
                'dusun'                   => $this->student->dusun,
                'kelurahan'               => $this->student->kelurahan,
                'kecamatan'               => $this->student->kecamatan,
                'kabupaten'               => $this->student->kabupaten,
                'provinsi'                => $this->student->provinsi,
                'kode_pos'                => $this->student->kode_pos,
                'jenis_tinggal'           => $this->student->jenis_tinggal,
                'alat_transportasi'       => $this->student->alat_transportasi,
                // Fisik
                'berat_badan'             => $this->student->berat_badan,
                'tinggi_badan'            => $this->student->tinggi_badan,
                'kebutuhan_khusus'        => $this->student->kebutuhan_khusus,
                'anak_ke'                 => $this->student->anak_ke,
                'jml_saudara_kandung'     => $this->student->jml_saudara_kandung,
                'jarak_rumah_kesekolah'   => $this->student->jarak_rumah_kesekolah,
                // Keluarga
                'no_kk'                   => $this->student->no_kk,
                'nik_ayah'                => $this->student->nik_ayah,
                'nama_ayah'               => $this->student->nama_ayah,
                'tahun_lahir_ayah'        => $this->student->tahun_lahir_ayah,
                'jenjang_pendidikan_ayah' => $this->student->jenjang_pendidikan_ayah,
                'pekerjaan_ayah'          => $this->student->pekerjaan_ayah,
                'penghasilan_ayah'        => $this->student->penghasilan_ayah,
                'nik_ibu'                 => $this->student->nik_ibu,
                'nama_ibu'                => $this->student->nama_ibu,
                'tahun_lahir_ibu'         => $this->student->tahun_lahir_ibu,
                'jenjang_pendidikan_ibu'  => $this->student->jenjang_pendidikan_ibu,
                'pekerjaan_ibu'           => $this->student->pekerjaan_ibu,
                'penghasilan_ibu'         => $this->student->penghasilan_ibu,
                'nik_wali'                => $this->student->nik_wali,
                'nama_wali'               => $this->student->nama_wali,
                'tahun_lahir_wali'        => $this->student->tahun_lahir_wali,
                'jenjang_pendidikan_wali' => $this->student->jenjang_pendidikan_wali,
                'pekerjaan_wali'          => $this->student->pekerjaan_wali,
                'penghasilan_wali'        => $this->student->penghasilan_wali,
            ]),

            'regForm' => $this->whenLoaded('regForm', fn() => [
                'title' => $this->regForm->title,
                'ta'    => $this->regForm->ta,
            ]),
        ];
    }
}