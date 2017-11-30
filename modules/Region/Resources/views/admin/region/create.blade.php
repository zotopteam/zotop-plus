@extends('core::layouts.dialog')

@section('content')
<div class="main scrollable">

    {form model="$region" route="region.store" method="post" class="m-5" autocomplete="off"}

        @include('region.form')

    {/form}
</div>
@endsection

@push('css')

@endpush

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
