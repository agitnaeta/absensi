@if ($crud->hasAccess('filter_monthly'))
  <div class="col-md-6">
      <form class="input-group">
          <select class="form-control" name="recap_month">
              <option value="">-Bulan-</option>
              @foreach($crud->get('recap_months') as $v)
                  <option {{$crud->get('f_recap_month') ==  $v ? 'selected': ''}} value="{{$v}}">{{$v}}</option>
              @endforeach
          </select>
          <button class="btn btn-primary"> Filter</button>
          @if($crud->hasAccess('export_salary_recap'))
              <a href="{{ url($crud->route.'/export?salary_recap=') }}{{$crud->get('f_recap_month')}}"
                 class="btn beginning text-capitalize btn-success">
                  <i class="la la-download"></i> Export
              </a>
              <a href="{{ url($crud->route.'/print?salary_recap=') }}{{$crud->get('f_recap_month')}}"
                 class="btn beginning text-capitalize btn-default">
                  <i class="la la-print"></i> Cetak Gaji
              </a>
          @endif
      </form>
  </div>
@endif
