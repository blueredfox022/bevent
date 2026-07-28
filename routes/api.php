<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\CertificateController;
use Illuminate\Support\Facades\Mail;
use App\Services\ResendService;

/*
|--------------------------------------------------------------------------
| PUBLIC API
|--------------------------------------------------------------------------
*/

Route::post('/admin/login', [AuthController::class, 'login']);

Route::get('/events', [EventController::class, 'index']);

Route::get('/events/{id}', [EventController::class, 'show']);

Route::post(
    '/events/{id}/register',
    [ParticipantController::class, 'register']
);
Route::get(
    '/participants/{id}/download-qr',
    [ParticipantController::class, 'downloadQr']
);
Route::get(
    '/participants/{id}/certificate',
    [ParticipantController::class, 'generateCertificate']
);

/*
|--------------------------------------------------------------------------
| ADMIN API
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post(
        '/admin/logout',
        [AuthController::class, 'logout']
    );

    Route::post(
        '/events',
        [EventController::class, 'store']
    );

    Route::put(
        '/events/{id}',
        [EventController::class, 'update']
    );

    Route::delete(
        '/events/{id}',
        [EventController::class, 'destroy']
    );

    Route::get(
        '/events/{id}/participants',
        [EventController::class, 'participants']
    );

    Route::post(
        '/attendance/scan',
        [ParticipantController::class, 'scanAttendance']
    );

    Route::get(
        '/participants/{id}/qr',
        [ParticipantController::class, 'generateQr']
    );

    Route::post(
        '/events/{id}/certificates/send',
        [CertificateController::class, 'sendAllByEvent']
    );
});



Route::get('/test-email', function () {

    $host = 'smtp.gmail.com';
    $port = 587;

    $start = microtime(true);

    $fp = @fsockopen($host, $port, $errno, $errstr, 15);

    if (!$fp) {
        return response()->json([
            'connected' => false,
            'errno' => $errno,
            'error' => $errstr,
            'time' => microtime(true) - $start,
        ]);
    }

    fclose($fp);

    return response()->json([
        'connected' => true,
        'time' => microtime(true) - $start,
    ]);
});
Route::get('/dns-test', function () {

    return [
        'host' => gethostbyname('smtp.gmail.com'),
        'dns' => dns_get_record('smtp.gmail.com', DNS_A),
    ];
});


Route::get('/port-test', function () {

    $tests = [
        ['host' => 'google.com', 'port' => 443],
        ['host' => 'smtp.gmail.com', 'port' => 587],
        ['host' => 'smtp.gmail.com', 'port' => 465],
    ];

    $results = [];

    foreach ($tests as $test) {
        $start = microtime(true);

        $fp = @fsockopen(
            $test['host'],
            $test['port'],
            $errno,
            $errstr,
            10
        );

        $results[] = [
            'host' => $test['host'],
            'port' => $test['port'],
            'connected' => (bool) $fp,
            'errno' => $errno,
            'error' => $errstr,
            'time' => round(microtime(true) - $start, 2),
        ];

        if ($fp) {
            fclose($fp);
        }
    }

    return response()->json($results);
});

Route::get('/smtp-ip-test', function () {

    $ip = '172.217.217.108'; // IP yang tadi didapat dari DNS

    $fp = @fsockopen($ip, 587, $errno, $errstr, 10);

    return [
        'connected' => (bool) $fp,
        'errno' => $errno,
        'error' => $errstr,
    ];
});


Route::get('/test-resend', function () {

    try {

        Mail::raw('Halo! Ini adalah email percobaan menggunakan Resend.', function ($message) {

            $message->to('emailanda@gmail.com')
                ->subject('Resend Test');
        });

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil dikirim.'
        ]);
    } catch (\Throwable $e) {

        return response()->json([
            'success' => false,
            'class' => get_class($e),
            'message' => $e->getMessage(),
        ], 500);
    }
});


use App\Services\BrevoService;

Route::get('/test-brevo', function (BrevoService $brevo) {

    return $brevo->send(
        'emailanda@gmail.com',
        'Test Brevo',
        '<h1>Email berhasil</h1>'
    );
});
