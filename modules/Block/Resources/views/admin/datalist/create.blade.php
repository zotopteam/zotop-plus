@extends('layouts.dialog')

@section('content')
    <div class="container-fluid">
        {form model="$datalist" route="block.datalist.store" id="datalist-form" method="post" autocomplete="off"}
        {field type="hidden" name="block_id" required="required"}
        {field type="hidden" name="source_id" required="required"}
        {field type="hidden" name="module" required="required"}
        @foreach ($fields as $field)            
        <div class="form-group">
            <label for="{{array_get($field, 'field.id')}}" class="form-label {{array_get($field, 'field.required')}}">
                {{array_get($field, 'label')}}
            </label>
            <div class="form-field">
                {{Form::field($field['field'])}}
            </div>                      
        </div>
        @endforeach

        {/form}
    </div>
@endsection

@push('js')
<script type="text/javascript">
    
    // 对话框设置
    currentDialog.callbacks['ok'] = function(){
        $('form.form').submit();
        return false;
    };

    // 表单提交
    $(function(){
        $('form.form').submited(function(msg){
            msg.onclose = function () {
                currentDialog.opener.location.reload();
            }
            currentDialog.close();            
        });
    })  
</script>
@endpush
