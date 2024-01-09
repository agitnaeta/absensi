<table class="border-1">
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
        <td>Nama</td><td>:{{$user->name}}</td>
        <td>Email</td><td>:{{$user->email}}</td>
    </tr>
</table>


