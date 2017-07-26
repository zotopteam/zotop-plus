<div class="radiogroup {{$class}} {{$column ? "radiogroup-column-{$column}" : ''}}">
    <div class="radiogroup-item">
        @foreach((array)$options as $k=>$v)
        <label class="radio">
            {field type="radio" name="$name" value="$k" id="$name.'-'.$k"}
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