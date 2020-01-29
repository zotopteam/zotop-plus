<div class="radiogroup {{$class}} {{$column ? "radiogroup-column-{$column}" : ''}}">
    <div class="radiogroup-row">
        @foreach((array)$options as $k=>$v)
            <div class="radiogroup-item d-inline-block">
                <input type="radio" id="{{$name}}-{{$k}}" class="form-control" name="{{$name}}" value="{{$k}}" {{($k==$value)?'checked':''}}/>
                <label for="{{$name}}-{{$k}}">{{$v}}</label>
            </div>
        @if($column && $loop->iteration%$column==0 && $loop->iteration < $loop->count)
        </div>
        <div class="radiogroup-row">
        @endif
        @endforeach
    </div>
</div>
