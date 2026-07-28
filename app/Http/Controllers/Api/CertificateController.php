<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class CertificateController extends Controller
{


    public function create($id)
    {
        $participant = Participant::with('event')
            ->findOrFail($id);


        if (!$participant->attendance_status) {

            return response()->json([
                'message' => 'Peserta belum melakukan absensi.'
            ], 400);
        }



        // Jika sudah pernah dibuat
        if ($participant->certificate_file) {

            return response()->json([

                'message' => 'Sertifikat sudah tersedia.',

                'certificate_url' =>
                Storage::disk('s3')
                    ->url($participant->certificate_file)

            ]);
        }



        $pdf = Pdf::loadView(
            'certificates.template',
            compact('participant')
        );



        $fileName =
            'certificates/certificate_' .
            $participant->id .
            '.pdf';



        Storage::disk('s3')->put(
            $fileName,
            $pdf->output()
        );



        $participant->update([
            'certificate_file' => $fileName
        ]);



        return response()->json([

            'message' => 'Sertifikat berhasil dibuat',

            'certificate_url' =>
            Storage::disk('s3')
                ->url($fileName)

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
                'message' => 'Sertifikat belum dibuat.'
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
