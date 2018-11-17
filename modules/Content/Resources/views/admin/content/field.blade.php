<div class="form-group {{$item.disabled ? 'd-none' : ''}}">
    <label for="{{$item.for}}" class="form-label {{$item.required ? 'required' : ''}}">
        {{$item.label}}
    </label>
    
    {{form::field($item.field)}}

    @if($item.help) 
        <span class="form-help">{{$item.help}}</span>
    @endif
</div>
