@extends(backpack_view('blank'))
@section('content')
    <form method="post" action="{{route('schedule.mass_update')}}">
        <div class="row">
            <div class="col">
               <h1> Daftar jadwal</h1>
            </div>
            <table class="table table-striped table-hover nowrap rounded card-table table-vcenter card d-table shadow-xs border-xs dataTable dtr-inline collapsed has-hidden-columns">
                <tr >
                    <td>Nomor</td>
                    <td>Nama Karyawan</td>
                    <td>Nama Jadwal</td>
                </tr>
                @foreach($users as $user)

                    <tr>
                            <td>{{$loop->index+1}}</td>
                        <td>
                            <input type="hidden" name="user_ids[]" value="{{$user->id}}"/>
                            {{$user->name}}
                        </td>
                        <td>
                            <select class="form-control" name="schedule_ids[]">
                                @foreach($schedules as $schedule)
                                    <option value="{{$schedule->id}}">{{$schedule->name}}</option>
                                @endforeach
                            </select>

                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="row">
            <button class="btn btn-dark"> {{"Update Jadwal"}}</button>
        </div>
    </form>
@endsection
