<div id="code-editor-{{$name}}">
    <textarea name="{{$name}}" style="display:none;">{{$value}}</textarea>
</div>
@push('css')
    @loadcss(Module::asset('core:editormd/css/editormd.min.css'))
@endpush
@push('js')
    @loadjs(Module::asset('core:editormd/editormd.min.js'))
    @if(!App::isLocale('zh-Hans'))
    @loadjs(Module::asset('core:editormd/languages/'.App::getLocale().'.js'))
    @endif

    <script type="text/javascript">
    $(function(){
        window.code_editor_{{$name}} = editormd("code-editor-{{$name}}", {!! json_encode($options) !!});
    });        
    </script>
@endpush
