<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Registrasi Berhasil</title>
</head>

<body style="font-family: Arial, sans-serif">

    <h2>Registrasi Berhasil</h2>

    <p>Halo <strong>{{ $participant->name }}</strong>,</p>

    <p>
        Terima kasih telah melakukan registrasi pada event:
    </p>

    <h3>{{ $participant->event->title }}</h3>

    <p>
        Berikut QR Code Anda.
    </p>

    <p>
        Simpan QR Code ini dan tunjukkan saat melakukan absensi.
    </p>

    <img src="{{ $message->embed($qrPath) }}" width="280" alt="QR Code">
    <hr>

    <p>
        Salam,<br>
        <strong>Campus Event</strong>
    </p>

</body>

</html>
