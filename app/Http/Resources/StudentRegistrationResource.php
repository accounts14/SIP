<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentRegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'school_id'     => $this->school_id,
            'student_id'    => $this->student_id,
            'registration_form_id' => $this->registration_form_id,
            'registered_by' => $this->registered_by,
            'school_origin' => $this->school_origin,
            'status'        => $this->status,
            'updated_at'    => $this->updated_at,
            'school'        => $this->whenLoaded('school', function() {
                return [
                    'name'          => $this->school->name,
                    'type'          => $this->school->type,
                    'accreditation' => $this->school->accreditation,
                    'level'         => $this->school->level,
                    'npsn'          => $this->school->npsn,
                ];
            }),
            'student' => $this->whenLoaded('student', function() {
                return [
                    'nama'  => $this->student->nama,
                    'nisn'  => $this->student->nisn,
                    'email' => $this->student->email,
                    'alamat'=> $this->student->alamat,
                ];
            }),
            'regForm' => $this->whenLoaded('regForm', function() {
                return [
                    'title'  => $this->regForm->title,
                    'ta'     => $this->regForm->ta,
                ];
            }),
        ];
    }
}