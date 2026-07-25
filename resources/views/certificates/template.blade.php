<!DOCTYPE html>
<html>
<head>
    <title>Certificate</title>

    <style>

        body {
            font-family: sans-serif;
            text-align: center;
            padding-top: 150px;
        }

        .title {
            font-size: 40px;
            font-weight: bold;
        }

        .participant {
            font-size: 30px;
            margin-top: 40px;
        }

        .event {
            font-size: 22px;
            margin-top: 20px;
        }

    </style>
</head>

<body>

    <div class="title">
        CERTIFICATE
    </div>

    <p>
        Diberikan kepada:
    </p>

    <div class="participant">
        {{ $participant->name }}
    </div>

    <div class="event">
        Sebagai peserta pada event:
        <br><br>

        <strong>
            {{ $participant->event->title }}
        </strong>
    </div>

</body>
</html>