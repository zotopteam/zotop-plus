@extends('layouts.dialog')

@section('content')

    {form route="core.file.editor" method="post" class="code-editor full-width full-height"}
        {field type="hidden" name="file" value="$file"}
        {field type="code" name="content" value="$content" width="100%" height="100%" mode="$mode"}
    {/form}

@endsection

@push('js')
<script type="text/javascript">
    // 对话框设置
    currentDialog.statusbar("{{trans('core::file.position')}} {{path_base($path)}}");
    // currentDialog.callback('ok', function(){
    //     $('form.form').submit();
    //     return false;
    // });

    currentDialog.button([
        {           
            value: '{{trans('master.save')}}',
            callback: function () {
                $('form.form').submit();
                return false;
            },
            autofocus: true
        },
        {           
            value: '{{trans('master.save.close')}}',
            callback: function () {
                this.saveclose = true;
                $('form.form').submit();
                return false;
            },
            class:'btn-success'
        },
        {
            value: '{{trans('master.cancel')}}'
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
        $('form.form').submited(function(){
            currentDialog.saveclose && currentDialog.close();
        });      
    })        
</script>
@endpush

@push('css')
<style type="text/css">
.editormd{margin:0 auto;border:0 none;}
</style>
@endpush
