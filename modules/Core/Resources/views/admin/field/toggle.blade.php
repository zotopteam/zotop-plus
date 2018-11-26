<div class="form-control-toggle {{$class}}" id="{{$id}}">
    <input type="checkbox" class="toggle" data-enable="{{$enable}}" data-disable="{{$disable}}" @if($value==$enable)checked="checked"@endif>
    <input type="hidden" name="{{$name}}" value="{{$value}}">
</div>

@push('js')
    @once('FIELD_TOGGLE_INIT_JS')
    <script type="text/javascript" src="{{Module::asset('core:js/field_toggle.js')}}"></script>
    @endonce
@endpush
