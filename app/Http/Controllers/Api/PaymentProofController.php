<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentProof;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    /**
     * List bukti pembayaran
     * - Siswa (member): hanya miliknya (filter by user_id)
     * - Admin sekolah / school_head: semua di sekolahnya
     * - admin:sip / superadmin: semua
     */
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = PaymentProof::with(['studentRegistration', 'verifier']);

        if ($user->role === 'member') {
            if ($request->registration_id) {
                // Jika filter by registration_id, pastikan registrasi ini memang milik user tsb
                // lalu tampilkan SEMUA proof termasuk yang diupload admin
                $ownReg = \DB::table('student_registrations')
                    ->join('user_members', 'user_members.student_id', '=', 'student_registrations.student_id')
                    ->where('user_members.user_id', $user->id)
                    ->where('student_registrations.id', $request->registration_id)
                    ->exists();
                if (!$ownReg) {
                    return response()->json(['data' => []], 200);
                }
                // Biarkan filter registration_id di bawah yang menangani
            } else {
                // Tanpa registration_id: filter by user_id seperti biasa
                $query->where('user_id', $user->id);
            }
        } elseif (in_array($user->role, ['admin:school_admin', 'admin:school_head'])) {
            $schoolId = $request->school_id ?? $user->school_id ?? null;
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            }
        }
        // admin:sip dan superadmin tidak difilter — lihat semua

        if ($request->registration_id) {
            $query->where('student_registration_id', $request->registration_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->get();
        return response()->json([
            'data' => $data->map(fn($p) => $this->formatProof($p)),
        ], 200);
    }

    /**
     * Upload bukti pembayaran (siswa atau admin)
     *
     * PENTING:
     * - student_registrations.student_id  → merujuk ke user_candidates.id
     * - payment_proofs.student_id FK       → merujuk ke users.id  (via user_members.user_id)
     * - Kita simpan KEDUANYA:
     *     student_id = user_candidates.id  (agar relasi data tetap konsisten)
     *     user_id    = users.id            (untuk memenuhi FK constraint ke tabel users)
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_registration_id' => 'required|exists:student_registrations,id',
            'file'                    => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes'                   => 'nullable|string|max:500',
        ]);

        $user       = auth()->user();
        $reg        = StudentRegistration::findOrFail($request->student_registration_id);
        $uploadedBy = in_array($user->role, ['admin:school_admin', 'admin:school_head', 'admin:sip', 'superadmin'])
                      ? 'admin' : 'student';

        // Resolve users.id yang akan disimpan di kolom user_id
        if ($uploadedBy === 'student') {
            // Siswa yang sedang login — gunakan ID-nya langsung
            $userId = $user->id;
        } else {
            // Admin upload untuk siswa:
            // cari users.id siswa via user_members (student_id = user_candidates.id)
            $userMember = DB::table('user_members')
                ->where('student_id', $reg->student_id)
                ->first();
            $userId = $userMember ? $userMember->user_id : $user->id;
        }

        $file     = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path     = $file->store('payment_proofs', 'public');

        $proof = PaymentProof::create([
            'student_registration_id' => $reg->id,
            'school_id'               => $reg->school_id,
            'student_id'              => $reg->student_id,  // user_candidates.id
            'user_id'                 => $userId,            // users.id (FK-safe)
            'file_path'               => $path,
            'file_name'               => $fileName,
            'notes'                   => $request->notes,
            'uploaded_by'             => $uploadedBy,
            'status'                  => 'pending',
        ]);

        return response()->json([
            'data' => $this->formatProof($proof),
            'msg'  => 'Bukti pembayaran berhasil diunggah.',
        ], 201);
    }

    public function update(Request $request, PaymentProof $paymentProof)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'notes'  => 'nullable|string|max:500',
        ]);

        $paymentProof->status      = $request->status;
        $paymentProof->verified_at = now();
        $paymentProof->verified_by = auth()->id();
        if ($request->notes) $paymentProof->notes = $request->notes;
        $paymentProof->save();

        return response()->json([
            'data' => $this->formatProof($paymentProof),
            'msg'  => 'Status bukti pembayaran diperbarui.',
        ], 200);
    }

    public function destroy(PaymentProof $paymentProof)
    {
        Storage::disk('public')->delete($paymentProof->file_path);
        $paymentProof->delete();
        return response()->json(['msg' => 'Bukti pembayaran dihapus.'], 200);
    }

    private function formatProof(PaymentProof $p): array
    {
        return [
            'id'                      => $p->id,
            'student_registration_id' => $p->student_registration_id,
            'school_id'               => $p->school_id,
            'student_id'              => $p->student_id,
            'user_id'                 => $p->user_id,
            'file_url'                => asset('storage/' . $p->file_path),
            'file_name'               => $p->file_name,
            'notes'                   => $p->notes,
            'uploaded_by'             => $p->uploaded_by,
            'status'                  => $p->status,
            'verified_at'             => $p->verified_at?->toDateTimeString(),
            'verifier_name'           => $p->verifier?->name,
            'created_at'              => $p->created_at?->toDateTimeString(),
        ];
    }
}