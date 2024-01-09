{{-- day_off_check_field field --}}
@php
    $field['value'] = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));

@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

<ul style="list-style-type: none">

    @foreach($field['option'] as $opsi)
      <li >
          <input type="checkbox"
                 class="check"
                 @if(in_array($opsi->id,$field['selected']))
                     checked="checked"
                 @endif
                 id="{{$opsi->id}}-check"
                 name="{{$field['name'] }}[]"
                 data-init-function="bpFieldInitDummyFieldElement"
                 value="{{$opsi->id }}"
              @include('crud::fields.inc.attributes')>
          <label for="{{$opsi->id}}-check">{{\Illuminate\Support\Str::upper($opsi->name)}}</label>
      </li>
    @endforeach
</ul>



    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- CUSTOM CSS --}}
@push('crud_fields_styles')
    {{-- How to load a CSS file? --}}
    @basset('day_off_checkFieldStyle.css')

    {{-- How to add some CSS? --}}
    @bassetBlock('backpack/crud/fields/day_off_check_field-style.css')
        <style>
            .day_off_check_field_class {
                display: none;
            }
        </style>
    @endBassetBlock
@endpush

{{-- CUSTOM JS --}}
@push('crud_fields_scripts')
    {{-- How to load a JS file? --}}
    @basset('day_off_checkFieldScript.js')

    {{-- How to add some JS to the field? --}}
    @bassetBlock('path/to/script.js')
    <script>
        function bpFieldInitDummyFieldElement(element) {
            // this function will be called on pageload, because it's
            // present as data-init-function in the HTML above; the
            // element parameter here will be the jQuery wrapped
            // element where init function was defined
            console.log(element.val());
        }
    </script>
    @endBassetBlock
@endpush
