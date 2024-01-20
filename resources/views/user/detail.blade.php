<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ID card</title>
    <style>
        html {
            margin: 0;
            padding: 0;
        }
        .text-center {
            text-align: center;
            font-size: 18px;
        }
        table {
            margin-top: 20px;
            width: 100%;
        }

        .mt {
            margin-bottom: 32px;
        }

        body {
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.5;
            background-size: cover;
            background-image: url({{ $company->id_card }});
        }

        .block-image {
            min-height: 150px;
        }
        .block-image > img.company{
            border-radius: 8px;
        }
        .block-image > img.profile{
            height: 150px;
            min-height: 150px;
            overflow: hidden;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
@foreach($users as $user)
    <table>
        <tr>
            <td class="text-center block-image">
                @if($company->image)
                    <img class="mt company" height="100" width="100" src="{{ $company->image }}">
                @endif
            </td>
        </tr>

        <tr>
            <td class="text-center block-image">
                @if($user->isUserImage)
                    <img class="mt profile" src="{{ $user->image }}">
                @endif
            </td>
        </tr>

        <tr>
            <td class="text-center block-image">
                @if($user->qr)
                    <img src="data:image/svg+xml;base64,{{ $user->qr }}">
                @endif
            </td>
        </tr>

        <tr>
            <td class="text-center"> {{ $user->name }}</td>
        </tr>
        <tr>
            <td class="text-center"> ({{ $company->name }})</td>
        </tr>
    </table>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
