@if ($crud->hasAccess('setPayment'))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/set-payment?method=transfer') }}" class="btn btn-sm btn-link text-capitalize">
      <i class="la la-send"></i>Bayar Transfer</a>
@endif
