
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Gaji</title>
    <style>
        table,
        th,
        td {
            border: 1px solid #ddd;
        }
        table{
            margin-top: 38px;
            width: 100%;
            border: 1px solid #73818f;
        }
        .mt{
            margin-bottom: 32px;
        }
        .page-break {
            page-break-before: always;
        }
        .logo{
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
            display: block;
            text-align: center;
        }
    </style>
</head>
<body>
    @foreach($recaps as $row)
            <div class="logo">
               @if($isCompanyImage)
                    <img height="100" width="100" src="{{$company->image}}"/>
                @endif
                <p>{{$company->name}}</p>
            </div>
        <table border="1">
            <tr>
                <td>Nama Karyawan</td>
                <td>{{ $row->user->name }}</td>
            </tr>
            <tr>
                <td>Bulan Ringkasan</td>
                <td>{{ $row->recap_month }}</td>
            </tr>
            <tr>
                <td>Hari Kerja</td>
                <td>{{ $row->work_day }}</td>
            </tr>
            <tr>
                <td>Jumlah Absen</td>
                <td>{{ $row->abstain_count }}</td>
            </tr>
            <tr>
                <td>Hari Terlambat</td>
                <td>{{ $row->late_day }}</td>
            </tr>
            <tr>
                <td>Total Menit Terlambat</td>
                <td>{{ $row->late_minute_count }}</td>
            </tr>
            <tr>
                <td>Jenis Denda</td>
                <td>{{ isset($row->user->salary->fine_type) ? $row->user->salary->fine_type : '' }}</td>
            </tr>
            <tr>
                <td>Jumlah Gaji</td>
                <td>@rupiah($row->salary_amount)</td>
            </tr>
            <tr>
                <td>Potongan Absen</td>
                <td>@rupiah($row->abstain_cut)</td>
            </tr>
            <tr>
                <td>Potongan Terlambat</td>
                <td>@rupiah($row->late_cut)</td>
            </tr>
            <tr>
                <td>Potongan Pinjaman</td>
                <td>@rupiah($row->loan_cut)</td>
            </tr>
            <tr>
                <td>Diterima</td>
                <td>@rupiah($row->received)</td>
            </tr>

            <tr>
                <td>Status Dibayar</td>
                <td>{{ $row->status ? 'Ya' : 'Belum' }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>{{ $row->desc }}</td>
            </tr>
            <tr>
                <td>Metode Pembayaran</td>
                <td>{{ $row->method }}</td>
            </tr>
        </table>
        <div class="page-break"></div>
    @endforeach

</body>
