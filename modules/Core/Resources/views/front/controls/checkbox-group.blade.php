<div class="checkboxgroup {{$class}} {{$column ? "checkboxgroup-column-{$column}" : ''}}">
    <div class="checkboxgroup-row">
        @foreach($options as $k=>$v)
            <div class="checkboxgroup-item d-inline-block">
                <input type="checkbox" class="form-control-check" id="{{$name}}-{{$k}}" {{$control->checkbox($k)}}/>
                <label class="form-control-label" for="{{$name}}-{{$k}}">
                    {{$v}}
                </label>
            </div>
            @if($column && $loop->iteration%$column==0)
    </div>
    <div class="checkboxroup-row">
        @endif
        @endforeach
    </div>
</div>