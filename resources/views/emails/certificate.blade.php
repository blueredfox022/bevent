<!DOCTYPE html>
<html>

<body>
    <h2>Halo, {{ $participant->name }}</h2>

    <p>
        Terima kasih telah mengikuti event
        <strong>{{ $participant->event->title }}</strong>.
    </p>

    <p>
        Sertifikat Anda terlampir dalam email ini.
    </p>
</body>

</html>
