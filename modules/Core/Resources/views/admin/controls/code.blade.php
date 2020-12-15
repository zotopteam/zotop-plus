<div id="code-editor-{{$name}}">
    <textarea name="{{$name}}" style="display:none;">{{$value}}</textarea>
</div>
@push('css')
    {!! Module::load('core:editormd/css/editormd.min.css') !!}
@endpush
@push('js')
    {!! Module::load('core:editormd/editormd.min.js') !!}
    {!! Module::load('core:editormd/languages/'.App::getLocale().'.js') !!}
    
    <script type="text/javascript">
    $(function(){
        window.code_editor_{{$name}} = editormd("code-editor-{{$name}}", {!! json_encode($options) !!});
    });        
    </script>
@endpush
