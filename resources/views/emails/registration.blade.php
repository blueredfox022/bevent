<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Registrasi Event Berhasil</title>
</head>

<body style="font-family:Arial,sans-serif;background:#f5f5f5;padding:30px;">

    <table width="600" align="center" style="background:#ffffff;padding:30px;border-radius:8px;">

        <tr>
            <td align="center">

                <h2 style="color:#16a34a;">
                    Registrasi Berhasil
                </h2>

                <p>
                    Halo <strong>{{ $participant->name }}</strong>,
                </p>

                <p>
                    Terima kasih telah mendaftar pada event:
                </p>

                <h3>
                    {{ $participant->event->title }}
                </h3>

                <p>
                    Berikut adalah QR Code Anda.
                </p>

                <img src="{{ $qrUrl }}" width="260" alt="QR Code">

                <p style="margin-top:25px;">
                    Gunakan QR Code ini saat melakukan absensi.
                </p>

                <p style="margin-top:25px;">
                    <a href="{{ $qrUrl }}"
                        style="
                            background:#16a34a;
                            color:white;
                            padding:12px 24px;
                            text-decoration:none;
                            border-radius:6px;
                        ">
                        Download QR Code
                    </a>
                </p>

                <hr style="margin:30px 0;">

                <p style="color:#777;">
                    Salam,
                    <br>
                    <strong>Campus Event</strong>
                </p>

            </td>
        </tr>

    </table>

</body>

</html>
