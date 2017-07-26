<div class="checkboxgroup {{$class}} {{$column ? "checkboxgroup-column-{$column}" : ''}}">
    <div class="checkboxgroup-item">
        @foreach((array)$options as $k=>$v)
        <label class="checkbox">
            {field type="checkbox" name="$name.'[]'" value="$k" checked="in_array($k, $value)" id="$name.'-'.$k"}
            <span class="checkbox-text">
                {{$v}}
            </span>
        </label>
        @if($column && $loop->iteration%$column==0)
        </div>
        <div class="checkboxroup-item">
        @endif
        @endforeach
    </div>
</div>