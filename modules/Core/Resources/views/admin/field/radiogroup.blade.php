<div class="radiogroup {{$class}} {{$column ? "radiogroup-column-{$column}" : ''}}">
    <div class="radiogroup-row">
        @foreach((array)$options as $k=>$v)
        <label class="radiogroup-item radio">
            <input type="radio" id="{{$name}}-{{$k}}" class="form-control" name="{{$name}}" value="{{$k}}" {{($k==$value)?'checked':''}}/>
            <span class="radio-text">
                {{$v}}
            </span>
        </label>
        @if($column && $loop->iteration%$column==0 && $loop->iteration < $loop->count)
        </div>
        <div class="radiogroup-row">
        @endif
        @endforeach
    </div>
</div>
