<div class="radiogroup {{$class}} {{$column ? "radiogroup-column-{$column}" : ''}}">
    <div class="radiogroup-item">
        @foreach((array)$options as $k=>$v)
        <label class="radio">
            <input type="radio" id="{{$name}}-{{$k}}" class="form-control" name="{{$name}}" value="{{$k}}" {{($k==$value)?'checked':''}}/>
            <span class="radio-text">
                {{$v}}
            </span>
        </label>
        @if($column && $loop->iteration%$column==0)
        </div>
        <div class="radiogroup-item">
        @endif
        @endforeach
    </div>
</div>