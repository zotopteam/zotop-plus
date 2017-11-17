@extends('core::layouts.dialog')

@section('content')
<div class="main">
    <div class="main-body scroll">
    {form route="core.file.editor" method="post" class="code-editor full-height"}
        {field type="hidden" name="file" value="$file"}
        {field type="code" name="content" value="$content" width="100%" height="100%" mode="$mode"}
    {/form}
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    // 对话框设置
    $dialog.statusbar("{{trans('core::file.position',[path_base($path)])}}");
    // $dialog.callback('ok', function(){
    //     $('form.form').submit();
    //     return false;
    // });

    $dialog.button([
        {           
            value: '{{trans('core::master.save')}}',
            callback: function () {
                $('form.form').submit();
                return false;
            },
            autofocus: true
        },
        {           
            value: '{{trans('core::master.save.close')}}',
            callback: function () {
                this.saveclose = true;
                $('form.form').submit();
                return false;
            },
            class:'btn-success'
        },
        {
            value: '{{trans('core::master.cancel')}}'
        }       
    ]);


    // 自动全屏
    $(function(){
        setTimeout(function(){
            //window.code_editor_content.fullscreen(); 
            //window.code_editor_content.focus(); 
        },1000);       
    });

    // 表单验证码提交
    $(function(){
        $('form.form').validate({
            submitHandler:function(form){                
                var validator = this;
                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    // 关闭对话框
                     msg.state && $dialog.saveclose && $dialog.close();                    
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

@push('css')
<style type="text/css">
.editormd{margin:0 auto;border:0 none;}
</style>
@endpush
