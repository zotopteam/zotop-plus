@extends('core::layouts.dialog')

@section('content')
<div class="main scrollable">

    {form model="$module" route="developer.module.store" method="post" class="m-4" autocomplete="off"}

        <div class="container-fluid">

            <div class="form-group">
                <label for="name" class="form-label required">{{trans('developer::module.name.label')}}</label>
                <div class="form-field">
                    {field type="text" name="name" pattern="^[a-z]+$" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::module.name.help')}}</span>                     
                    @endif
                </div>                      
            </div>

            <div class="form-group">
                <label for="plain" class="form-label required">{{trans('developer::module.plain.label')}}</label>
                <div class="form-field">
                    {field type="radiogroup" name="plain" options="$plains" required="required" column="1"}

                    @if ($errors->has('plain'))
                    <span class="form-help text-error">{{ $errors->first('plain') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::module.plain.help')}}</span>                     
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
