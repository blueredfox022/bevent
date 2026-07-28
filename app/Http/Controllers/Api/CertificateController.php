<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Mail\CertificateMail;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Models\Participant;
use Illuminate\Support\Facades\Storage;


class CertificateController extends Controller
{
    public function sendAllByEvent($eventId)
    {
        $event = Event::with([
            'participants' => function ($query) {
                $query->where('attendance_status', true);
            }
        ])->findOrFail($eventId);

        if ($event->participants->count() === 0) {
            return response()->json([
                'message' => 'Belum ada peserta hadir pada event ini'
            ], 400);
        }

        $sent = 0;

        foreach ($event->participants as $participant) {
            $participant->load('event');

            $pdf = Pdf::loadView('certificates.template', [
                'participant' => $participant,
            ]);

            Mail::to($participant->email)
                ->send(new CertificateMail(
                    $participant,
                    $pdf->output()
                ));

            $sent++;
        }

        return response()->json([
            'message' => 'Sertifikat berhasil dikirim',
            'total_sent' => $sent,
            'event' => $event->title,
        ]);
    }
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


        if (!$participant->certificate_file) {
            return response()->json([
                'message' => 'Sertifikat belum tersedia.'
            ], 400);
        }


        return response()->json([
            'name' => $participant->name,
            'certificate_url' =>
            Storage::disk('s3')
                ->url($participant->certificate_file)
        ]);
    }
}
