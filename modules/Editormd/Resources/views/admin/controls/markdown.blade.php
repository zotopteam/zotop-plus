<div id="md-editor-{{$name}}">
    <textarea name="{{$name}}" style="display:none;">{{$value}}</textarea>
</div>
@push('css')
    {!! Module::load('editormd:editormd/css/editormd.min.css') !!}
@endpush
@push('js')
    {!! Module::load('editormd:editormd/editormd.min.js') !!}
    {!! Module::load('editormd:editormd/languages/'.App::getLocale().'.js') !!}

    <script type="text/javascript">
        $(function () {
            window.md_editor_{{$name}} = editormd("md-editor-{{$name}}", {!! json_encode($options) !!});
        });
    </script>
@endpush
