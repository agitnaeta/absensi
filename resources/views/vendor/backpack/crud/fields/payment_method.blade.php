{{-- payment_method_field field --}}
@php
    $field['value'] = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    <select name="{{$field['name']}}" data-init-function="bpFieldInitDummyFieldElement"
        @include('crud::fields.inc.attributes')>
        <option value="">-Pilih-</option>
        <option {{$field['value'] =='cash' ? 'selected' : ''}} value="cash">Cash</option>
        <option {{$field['value'] =='transfer' ? 'selected' : ''}} value="cash">Transfer</option>
    </select>
    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- CUSTOM CSS --}}
@push('crud_fields_styles')
    {{-- How to load a CSS file? --}}
    @basset('payment_methodFieldStyle.css')

    {{-- How to add some CSS? --}}
    @bassetBlock('backpack/crud/fields/payment_method_field-style.css')
        <style>
            .payment_method_field_class {
                display: none;
            }
        </style>
    @endBassetBlock
@endpush

{{-- CUSTOM JS --}}
@push('crud_fields_scripts')
    {{-- How to load a JS file? --}}
    @basset('payment_methodFieldScript.js')

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
