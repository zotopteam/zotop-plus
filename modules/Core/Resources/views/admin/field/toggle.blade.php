<div class="form-control-toggle {{$class}}" id="{{$id}}">
    <input type="checkbox" class="toggle" data-enable="{{$enable}}" data-disable="{{$disable}}" @if($value==$enable)checked="checked"@endif>
    <input type="hidden" name="{{$name}}" value="{{$value}}">
</div>

@push('js')
    @loadjs(Module::asset('core:js/field_toggle.js'))
@endpush
