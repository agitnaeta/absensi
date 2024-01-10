@extends(backpack_view('blank'))
@section('content')
    <div class="container-fluid animated fadeIn">
        <h1>Rekap Kasbon {{$user->name}}</h1>
        <div>
            <h3 class="alert">Sisa Kasbon @rupiah($loan['total'])</h3>
        </div>
        <div class="row">
                <div class="col">
                    <table class="table table-striped table-hover nowrap rounded card-table table-vcenter card d-table shadow-xs border-xs dataTable dtr-inline collapsed has-hidden-columns">
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
                <div class="col">
                    <table class="table table-striped table-hover nowrap rounded card-table table-vcenter card d-table shadow-xs border-xs dataTable dtr-inline collapsed has-hidden-columns">
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
    </div>
@endsection
