@extends('core::layouts.dialog')

@section('content')
<div class="container-fluid">
    {form model="$datalist" route="['block.datalist.update', $datalist->id]" id="datalist-form" method="put" autocomplete="off"}

    {field type="hidden" name="block_id" required="required"}

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
    dialog.callbacks['ok'] = function(){
        $('form.form').submit();
        return false;
    };

    // 表单提交
    $(function(){
        $('form.form').validate({
            submitHandler:function(form){                
                var validator = this;
                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    
                    // 关闭对话框，刷新页面
                    if (msg.state) {
                        msg.onclose = function () {
                            dialog.opener.location.reload();
                        }
                        dialog.close();
                    }

                    // 弹出消息
                    $.msg(msg);

                },'json').fail(function(jqXHR){
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
    })  
</script>
@endpush
