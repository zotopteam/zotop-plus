<div class="checkboxgroup d-inline-block {{$class}} {{$column ? "checkboxgroup-column-{$column}" : ''}}"  name="{{$name}}" required="required">
    <div class="checkboxgroup-item">
        @foreach((array)$options as $k=>$v)
        <label class="checkbox">
            {field type="checkbox" name="$name.'[]'" value="$k" checked="in_array($k, $value)" id="$name.'-'.$k" class="$name.'-valid'"} 
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

@push('js')
<script type="text/javascript">
// $.validator.addClassRules("roles-valid", {
//   required: true,
//   minlength: 2
// });    
</script>
@endpush
