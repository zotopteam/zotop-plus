<div class="form-group row {{$item.disabled ? 'd-none' : ''}}">
    <label for="{{$item.for}}" class="col-2 col-form-label {{$item.required ? 'required' : ''}}">
        {{$item.label}}
    </label>

    <div class="col-10">
        {{form::field($item.field)}}

        @if($item.help)
            <span class="form-help">{{$item.help}}</span>
        @endif
    </div>
</div>
