@extends('core::layouts.dialog')

@section('content')
<div class="main scrollable">

    {form model="$category" route="['block.category.update', $category->id]" method="put" id="category-form" class="p-3" autocomplete="off"}

        <div class="container-fluid">

            <div class="form-group">
                <label for="name" class="form-label required">{{trans('block::category.name')}}</label>
                <div class="form-field">
                    {field type="text" name="name" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('block::category.name.help')}}</span>                     
                    @endif
                </div>                      
            </div>                                            

            <div class="form-group">
                <label for="description" class="form-label">{{trans('block::category.description')}}</label>
                <div class="form-field">
                    {field type="textarea" name="description" rows="5"}

                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('block::category.description.help')}}</span>                     
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
    dialog.callbacks['ok'] = function(){
        $('form.form').submit();
        return false;
    };

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
