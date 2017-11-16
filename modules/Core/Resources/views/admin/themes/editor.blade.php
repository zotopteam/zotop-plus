@extends('core::layouts.dialog')

@section('content')
<div class="main">
    <div class="main-body scroll">
    {form route="['core.themes.editor', $name]"}
        {field type="hidden" name="file" value="$file"}
        {field type="code" name="content" value="$content" width="100%" height="100%"}
    {/form}
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    // 对话框设置
    $dialog.statusbar("{{trans('core::themes.path',[$file])}}");
    $dialog.callbacks['ok'] = function(){
        location.reload();
        //$('form.form').submit();
        return false;
    };

    // 自动全屏
    $(function(){
        setTimeout(function(){
            //window.code_editor_content.fullscreen(); 
            //window.code_editor_content.focus(); 
        },1000);       
    });   
</script>
@endpush

@push('css')
<style type="text/css">
.editormd{margin:0 auto;border:0 none;}
</style>
@endpush
