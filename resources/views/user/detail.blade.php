<table class="border-1">
    <tr>
        <td colspan="2" style="text-align: center">{{"Kartu Absensi Balqis Syar'i"}}</td>
    </tr>
    <tr>
        <td colspan="2">
            @php
                $base = base64_encode( QrCode::size(200)
                        ->generate($user->qr));
            @endphp
            <img src="data:image/svg+xml;base64,{{$base}}">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <br>
        </td>
    </tr>
    <tr>
        <td width="30%">Nama</td><td>: {{$user->name}}</td>

    </tr>
    <tr>
        <td>Email</td><td>: {{$user->email}}</td>
    </tr>
</table>


