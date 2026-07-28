<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\CertificateController;
use Illuminate\Support\Facades\Mail;

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
