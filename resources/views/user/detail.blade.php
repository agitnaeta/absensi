
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ID card</title>
    <style>
        html{
            margin: 0;
            padding: 0;
        }
        .text-center{
            text-align: center;
            font-size: 24px;
        }
        table{
            margin-top: 38px;
            width: 100%;
        }
        .mt{
            margin-bottom: 32px;
        }
        body{
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.5;
            background-image: url({{$company->image}});
        }
    </style>
</head>
<body>
<table>
    <tr>
        <td class="text-center">{{$company->name}}</td>
    </tr>
    @if($isUserImage)
        <tr>
            <td class="text-center">
                {{--            <img src="data:image/svg+xml;base64,{{$userImage}}">--}}
                <img  class="mt" height="150" width="150" src="{{$userImage}}">
            </td>
        </tr>
    @endif
    <tr>
        <td class="text-center">
            @php
                $base = base64_encode( QrCode::size(200)
                        ->generate($user->qr));
            @endphp
            <img src="data:image/svg+xml;base64,{{$base}}">
        </td>
    </tr>
    <tr>
        <td>
            <br>
        </td>
    </tr>
    <tr>
        <td class="text-center"> {{$user->name}}</td>
    </tr>
</table>
</body>
</html>


