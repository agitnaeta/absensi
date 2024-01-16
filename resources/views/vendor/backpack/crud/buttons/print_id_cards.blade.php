@if ($crud->hasAccess('print_id_cards'))
  <a href="{{ route('user.print.all') }}"
     class="btn btn-secondary text-capitalize"><i class="la la-print"></i> Cetak Semua ID</a>
@endif
