<div class="form-group {{$item.disabled ? 'd-none' : ''}} {{$item.width}} px-3">
    <label for="{{$item.for}}" class="form-label {{$item.required ? 'required' : ''}}">
        {{$item.label}}
    </label>
    
    {{form::field($item.field)}}

    @if($item.help) 
        <span class="form-help">{{$item.help}}</span>
    @endif
</div>
