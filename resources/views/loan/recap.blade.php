@extends(backpack_view('blank'))
@section('content')
    <div class="container-fluid animated fadeIn">
        <h1>Rekap Kasbon</h1>
        <div class="row">
           <div class="col mb-2">
               <a class="btn btn-primary " href="{{route('loan.download')}}" target="_blank">
                  <i class="la la-download"></i> Download Rekap
               </a>
           </div>
        </div>
        <div class="row">
            <table class="table table-striped table-hover nowrap rounded card-table table-vcenter card d-table shadow-xs border-xs dataTable dtr-inline collapsed has-hidden-columns">
                <thead>
                <tr>
                    <th>ID User</th>
                    <th>Nama</th>
                    <th>Kasbon</th>
                    <th>Terbayar</th>
                    <th>Sisa</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($loans as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>@rupiah($user->kasbon)</td>
                        <td>@rupiah($user->terbayar)</td>
                        <td>@rupiah($user->selisih)</td>
                        <td>
                            <a class="btn btn-link btn-sm" href="{{route('loan.detail',['id'=>$user->id])}}">
                                <i class="la la-print"></i>    Lihat Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
