@if ($crud->hasAccess('user_export'))
  <a href="{{ url($crud->route.'/export') }}" class="btn btn-success text-capitalize">
      <i class="la la-download"></i> User Export
  </a>
@endif
