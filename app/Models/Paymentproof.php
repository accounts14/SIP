<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentProof extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_registration_id',
        'school_id',
        'student_id',   // user_candidates.id
        'user_id',      // users.id  ← kolom baru, untuk FK constraint
        'file_path',
        'file_name',
        'notes',
        'uploaded_by',
        'status',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function studentRegistration()
    {
        return $this->belongsTo(StudentRegistration::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relasi ke users.id (siswa login)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke users.id (admin verifikator)
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}