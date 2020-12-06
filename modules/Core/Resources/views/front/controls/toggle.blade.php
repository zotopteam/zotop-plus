<div class="form-control-toggle {{$class}}" {{$attributes}}>
    <input {{$toggle}}>
    <input type="hidden" name="{{$name}}" value="{{$value}}">
</div>

@push('js')
    {!! Module::load('core:js/field_toggle.js') !!}
@endpush
