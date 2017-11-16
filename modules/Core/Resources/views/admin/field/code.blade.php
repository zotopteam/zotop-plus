<div id="code-editor-{{$name}}">
    <textarea name="{{$name}}" style="display:none;">{{$value}}</textarea>
</div>
@push('css')
    @once('code_editor_css')
    <link rel="stylesheet" type="text/css" href="{{Module::asset('core:editormd/css/editormd.min.css')}}">
    @endonce
@endpush
@push('js')
    @once('code_editor_js')
    <script type="text/javascript" src="{{Module::asset('core:editormd/editormd.min.js')}}"></script>
    @if(!App::isLocale('zh-Hans'))
    <script type="text/javascript" src="{{Module::asset('core:editormd/languages/'.App::getLocale().'.js')}}"></script>
    @endif
    @endonce

    <script type="text/javascript">
    $(function(){
        window.code_editor_{{$name}} = editormd("code-editor-{{$name}}", {!! json_encode($options) !!});
    });        
    </script>
@endpush
