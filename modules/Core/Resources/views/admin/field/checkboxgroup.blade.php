<div class="checkboxgroup d-inline-block {{$class}} {{$column ? "checkboxgroup-column-{$column}" : ''}}"  name="{{$name}}" required="required">
    <div class="checkboxgroup-row">
        @foreach((array)$options as $k=>$v)
            <div class="checkboxgroup-item d-inline-block">
            <z-field type="checkbox" name="$name.'[]'" value="$k" checked="in_array($k, $value)" class="$name.'-valid'" label="$v"/>
            </div> 
        @if($column && $loop->iteration%$column==0)
        </div>
        <div class="checkboxroup-row">
        @endif
        @endforeach
    </div>
</div>
