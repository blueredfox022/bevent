<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;


class ParticipantController extends Controller
{
    public function register(Request $request, $eventId)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'nim'        => ['required', 'digits_between:8,20'],
            'email'      => ['required', 'email'],
            'phone'      => ['required', 'digits_between:10,15'],
            'faculty'    => 'required|string|max:255',
            'department' => 'required|string|max:255',
        ]);

        try {

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
                ->where('nim', $validated['nim'])
                ->exists()
            ) {
                return response()->json([
                    'message' => 'NIM sudah terdaftar pada event ini.'
                ], 400);
            }


            // Generate Token QR
            $qrToken = strtoupper(Str::random(10));

            // Simpan peserta
            $participant = Participant::create([
                'event_id'          => $event->id,
                'name'              => $validated['name'],
                'nim'               => $validated['nim'],
                'email'             => $validated['email'],
                'phone'             => $validated['phone'],
                'faculty'           => $validated['faculty'],
                'department'        => $validated['department'],
                'qr_token'          => $qrToken,
                'attendance_status' => false,
            ]);

            $participant->load('event');

            // Generate QR Code
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($participant->qr_token)
                ->size(800)
                ->margin(20)
                ->build();

            // Path QR
            $qrPath = 'qrcodes/participant_' . $participant->id . '.png';

            // Upload ke Supabase Storage
            Storage::disk('s3')->put(
                $qrPath,
                $result->getString()
            );

            // Simpan path QR
            $participant->update([
                'qr_image' => $qrPath,
            ]);

            // Ambil URL Public QR
            // Ambil URL Public QR
            $qrUrl = Storage::disk('s3')->url($participant->qr_image);


            return response()->json([
                'message' => 'Registrasi berhasil.',
                'participant' => $participant->fresh(),
                'qr_url' => $qrUrl,
            ], 201);
        } catch (\Throwable $e) {

            Log::error($e);

            return response()->json([
                'message' => 'Terjadi kesalahan saat registrasi.',
                'error'   => config('app.debug')
                    ? $e->getMessage()
                    : null,
            ], 500);
        }
    }

    public function generateQr($id)
    {
        $participant = Participant::findOrFail($id);

        return response()->json([
            'participant' => $participant,
            'qr_url' => Storage::disk('s3')->url($participant->qr_image),
        ]);
    }

    public function downloadQr($id)
    {
        $participant = Participant::findOrFail($id);

        return redirect()->away(
            Storage::disk('s3')->url($participant->qr_image)
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
}
