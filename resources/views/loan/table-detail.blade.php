<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .table-container {
            display: inline-block;
            margin-right: 20px; /* Adjust the margin as needed for spacing between tables */
            vertical-align: top; /* Align the tables to the top */
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
    <title>Side-by-Side Tables</title>
</head>

<body>
<h2>Rekap Kasbon {{$user->name}}</h2>
<div>
    <h3>Sisa Kasbon @rupiah($loan['total'])</h3>
</div>
<div class="table-container">
    <div>
        <table>
            <tr>
                <td colspan="3">Rekap Kasbon</td>
            </tr>
            <tr>
                <td>No</td>
                <td>Tanggal</td>
                <td class="text-right">Jumlah</td>
            </tr>
            @foreach($loan['loan'] as $l)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$l->date_label}}</td>
                    <td class="text-right">@rupiah($l->amount)</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">Total</td>
                <td class="text-right">@rupiah($loan['loan']->sum('amount'))</td>
            </tr>
        </table>
    </div>
</div>

<div class="table-container">
    <div>
        <table>
            <tr>
                <td colspan="3">Rekap Pembayaran</td>
            </tr>
            <tr>
                <td>No</td>
                <td>Tanggal</td>
                <td class="text-right">Jumlah</td>
            </tr>
            @foreach($loan['loanPayment'] as $l)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$l->date_label}}</td>
                    <td class="text-right">@rupiah($l->amount)</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">Total</td>
                <td class="text-right">@rupiah($loan['loanPayment']->sum('amount'))</td>
            </tr>
        </table>
    </div>

</div>
</body>

</html>
