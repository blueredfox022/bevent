<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    /**
     * Generate sertifikat peserta
     */
    public function create($id)
    {
        try {

            $participant = Participant::with('event')
                ->findOrFail($id);


            // Cek kehadiran
            if (!$participant->attendance_status) {
                return response()->json([
                    'message' => 'Peserta belum melakukan absensi.'
                ], 400);
            }


            // Cek event menggunakan sertifikat
            if (!$participant->event->use_certificate) {
                return response()->json([
                    'message' => 'Event ini tidak menyediakan sertifikat.'
                ], 400);
            }


            // Jika sertifikat sudah ada
            if (
                $participant->certificate_file &&
                Storage::disk('s3')->exists($participant->certificate_file)
            ) {

                return response()->json([
                    'message' => 'Sertifikat sudah tersedia.',
                    'name' => $participant->name,
                    'certificate_file' => $participant->certificate_file
                ]);
            }


            // Generate PDF
            $pdf = Pdf::loadView(
                'certificates.template',
                compact('participant')
            );


            // Path file
            $fileName = 'certificates/certificate_'
                . $participant->id
                . '.pdf';


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
                'name' => $participant->name,
                'certificate_file' => $fileName
            ]);
        } catch (\Throwable $e) {

            Log::error($e);

            return response()->json([
                'message' => 'Terjadi kesalahan saat membuat sertifikat.',
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,
            ], 500);
        }
    }



    /**
     * Cek sertifikat berdasarkan NIM
     */
    public function check($nim)
    {
        $participant = Participant::where('nim', $nim)
            ->first();


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
            !Storage::disk('s3')
                ->exists($participant->certificate_file)
        ) {

            return response()->json([
                'message' => 'Sertifikat belum tersedia.'
            ], 400);
        }


        return response()->json([
            'name' => $participant->name,
            'certificate_file' => $participant->certificate_file
        ]);
    }
}
