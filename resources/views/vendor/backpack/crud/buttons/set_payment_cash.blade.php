@if ($crud->hasAccess('setPayment'))
  <a href="{{ url($crud->route.'/'.$entry->getKey().'/set-payment?method=cash') }}" class="btn btn-sm btn-link text-capitalize">
      <i class="la la-money"></i>Bayar Cash</a>
@endif
