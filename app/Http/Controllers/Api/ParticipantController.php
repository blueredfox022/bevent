<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationMail;
use App\Models\Event;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class ParticipantController extends Controller
{
    public function register(Request $request, $eventId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => ['required', 'digits_between:8,20'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'digits_between:10,15'],
            'faculty' => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        $event = Event::findOrFail($eventId);

        // Cek kuota
        if (
            $event->quota > 0 &&
            $event->participants()->count() >= $event->quota
        ) {
            return response()->json([
                'message' => 'Kuota event telah penuh.'
            ], 400);
        }

        // Cek NIM
        if (
            Participant::where('event_id', $event->id)
            ->where('nim', $request->nim)
            ->exists()
        ) {
            return response()->json([
                'message' => 'NIM sudah terdaftar pada event ini.'
            ], 400);
        }

        // Cek Email
        if (
            Participant::where('event_id', $event->id)
            ->where('email', $request->email)
            ->exists()
        ) {
            return response()->json([
                'message' => 'Email sudah digunakan pada event ini.'
            ], 400);
        }

        // Generate Token
        $qrToken = strtoupper(Str::random(10));

        // Simpan Peserta
        $participant = Participant::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'phone' => $request->phone,
            'faculty' => $request->faculty,
            'department' => $request->department,
            'qr_token' => $qrToken,
            'attendance_status' => false,
        ]);

        $participant->load('event');

        // Nama File
        $fileName = 'participant_' . $participant->id . '.png';

        // Generate QR PNG
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($participant->qr_token)
            ->size(800)
            ->margin(20)
            ->build();

        // Simpan ke storage
        Storage::disk('public')->put(
            'qrcodes/' . $fileName,
            $result->getString()
        );

        // Simpan nama file ke database
        $participant->update([
            'qr_image' => $fileName
        ]);

        // URL untuk frontend React
        $qrUrl = asset('storage/qrcodes/' . $fileName);

        // Path file untuk email
        $qrPath = storage_path(
            'app/public/qrcodes/' . $fileName
        );

        // Kirim email
        try {
            Mail::to($participant->email)
                ->send(
                    new RegistrationMail(
                        $participant,
                        $qrPath
                    )
                );
        } catch (\Throwable $e) {

            \Log::error($e);

            return response()->json([
                'message' => 'Email gagal dikirim',
                'error' => $e->getMessage()
            ], 500);
        }



        // Response ke frontend
        return response()->json([
            'message' => 'Registrasi berhasil.',
            'participant' => $participant->fresh(),
            'qr_url' => $qrUrl,
            'download_qr_url' => url("/api/participants/{$participant->id}/download-qr")
        ], 201);
    }
    public function generateQr($id)
    {
        $participant = Participant::findOrFail($id);

        return response()->json([
            'participant' => $participant,
            'qr_url' => asset(
                'storage/qrcodes/' .
                    $participant->qr_image
            )
        ]);
    }

    public function downloadQr($id)
    {
        $participant = Participant::findOrFail($id);

        $path = storage_path('app/public/qrcodes/' . $participant->qr_image);

        if (
            !$participant->qr_image ||
            !file_exists($path)
        ) {
            return response()->json([
                'message' => 'QR tidak ditemukan.'
            ], 404);
        }

        return response()->download(
            $path,
            'QR-' . $participant->name . '.png'
        );
    }

    public function scanAttendance(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string'
        ]);

        $participant = Participant::where('qr_token', $request->qr_token)->first();

        if (!$participant) {
            return response()->json([
                'message' => 'QR Code tidak valid.'
            ], 404);
        }

        if ($participant->attendance_status) {
            return response()->json([
                'message' => 'Peserta sudah melakukan absensi.'
            ], 400);
        }

        $participant->update([
            'attendance_status' => true
        ]);

        return response()->json([
            'message' => 'Absensi berhasil.',
            'participant' => $participant
        ]);
    }

    public function generateCertificate($id)
    {
        $participant = Participant::with('event')->findOrFail($id);

        // Jika event tidak menggunakan sertifikat
        if (!$participant->event->use_certificate) {
            return response()->json([
                'message' => 'Event ini tidak menyediakan sertifikat.'
            ], 400);
        }

        if (!$participant->attendance_status) {
            return response()->json([
                'message' => 'Peserta belum melakukan absensi.'
            ], 400);
        }

        $pdf = Pdf::loadView('certificates.template', compact('participant'));

        return $pdf->download('certificate-' . $participant->name . '.pdf');
    }
}
