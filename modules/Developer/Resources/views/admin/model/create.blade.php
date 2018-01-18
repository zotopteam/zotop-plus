@extends('core::layouts.dialog')

@section('content')
<div class="main scrollable">

    {form route="['developer.model.create', $module]" method="post" class="p-3" autocomplete="off"}

        <div class="container-fluid">

            <div class="form-group">
                <label for="name" class="form-label required">{{trans('developer::model.name')}}</label>
                <div class="form-field">
                    {field type="text" name="name" pattern="^[a-zA-z]+$" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::model.name.help')}}</span>                     
                    @endif
                </div>                      
            </div>                                            

            <div class="form-group">
                <label for="fillable" class="form-label required">{{trans('developer::model.fillable')}}</label>
                <div class="form-field">
                    {field type="text" name="fillable" pattern="^[a-zA-z0-9,]+$"}

                    @if ($errors->has('fillable'))
                    <span class="form-help text-error">{{ $errors->first('fillable') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::model.fillable.help')}}</span>                     
                    @endif
                </div>                      
            </div>

            <div class="form-group">
                <label for="migration" class="form-label required">{{trans('developer::model.migration')}}</label>
                <div class="form-field">
                    {field type="toggle" name="migration"}

                    @if ($errors->has('migration'))
                    <span class="form-help text-error">{{ $errors->first('migration') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::model.migration.help')}}</span>                     
                    @endif
                </div>                      
            </div>

        </div>

    {/form}
</div>


@endsection

@push('js')
<script type="text/javascript">

    // 对话框设置
    $dialog.callbacks['ok'] = function(){
        $('form.form').submit();
        return false;
    };

    $(function(){

        $('form.form').validate({
       
            submitHandler:function(form){                
                var validator = this;
                $.post($(form).attr('action'), $(form).serialize(), function(msg){

                    // 关闭对话框
                    msg.state && $dialog.close();                    
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
