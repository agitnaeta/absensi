@if ($crud->hasAccess('export_salary_recap'))
  <div class="col-md-2">
      <a href="{{ url($crud->route.'/export') }}"
         class="btn beginning text-capitalize btn-success">
          <i class="la la-download"></i> Export
      </a>
  </div>
@endif
