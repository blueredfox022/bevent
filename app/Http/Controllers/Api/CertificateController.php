<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Membentuk URL public Supabase Storage
     */
    private function publicStorageUrl(string $path): string
    {
        return rtrim(config('filesystems.disks.s3.url'), '/')
            . '/'
            . ltrim($path, '/');
    }

    /**
     * Generate sertifikat
     */
    public function create($id)
    {
        $participant = Participant::with('event')->findOrFail($id);

        // Peserta harus sudah hadir
        if (!$participant->attendance_status) {
            return response()->json([
                'message' => 'Peserta belum melakukan absensi.'
            ], 400);
        }

        // Jika event tidak menggunakan sertifikat
        if (!$participant->event->use_certificate) {
            return response()->json([
                'message' => 'Event ini tidak menyediakan sertifikat.'
            ], 400);
        }

        // Jika sertifikat sudah pernah dibuat
        if (
            $participant->certificate_file &&
            Storage::disk('s3')->exists($participant->certificate_file)
        ) {
            return response()->json([
                'message' => 'Sertifikat sudah tersedia.',
                'certificate_url' => $this->publicStorageUrl(
                    $participant->certificate_file
                )
            ]);
        }

        // Generate PDF
        $pdf = Pdf::loadView(
            'certificates.template',
            compact('participant')
        );

        $fileName = 'certificates/certificate_' . $participant->id . '.pdf';

        // Upload ke Supabase Storage
        Storage::disk('s3')->put(
            $fileName,
            $pdf->output()
        );

        // Simpan path ke database
        $participant->update([
            'certificate_file' => $fileName
        ]);

        return response()->json([
            'message' => 'Sertifikat berhasil dibuat.',
            'certificate_url' => $this->publicStorageUrl($fileName)
        ]);
    }

    /**
     * Cek sertifikat berdasarkan NIM
     */
    public function check($nim)
    {
        $participant = Participant::where('nim', $nim)->first();

        if (!$participant) {
            return response()->json([
                'message' => 'Data peserta tidak ditemukan.'
            ], 404);
        }

        if (!$participant->attendance_status) {
            return response()->json([
                'message' => 'Peserta belum melakukan absensi.'
            ], 400);
        }

        if (
            !$participant->certificate_file ||
            !Storage::disk('s3')->exists($participant->certificate_file)
        ) {
            return response()->json([
                'message' => 'Sertifikat belum tersedia.'
            ], 400);
        }

        return response()->json([
            'name' => $participant->name,
            'download_url' => $this->publicStorageUrl(
                $participant->certificate_file
            )
        ]);
    }
}
