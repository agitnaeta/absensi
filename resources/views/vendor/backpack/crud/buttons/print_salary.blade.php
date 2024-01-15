@if ($crud->hasAccess('print_salary'))
  <a href="{{ url($crud->route.'/print?id=') }}{{$entry->getKeY()}}" class="btn btn-sm btn-link text-capitalize">
      <i class="la la-print"></i> Cetak Gaji</a>
@endif
