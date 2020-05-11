<div id="md-editor-{{$name}}">
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
        window.md_editor_{{$name}} = editormd("md-editor-{{$name}}", {!! json_encode($options) !!});

        // $('form').on('submit', function(){
        //     $('#md-editor-{{$name}}').find('textarea').val(function(){
        //         return md_editor_{{$name}}.getHTML();
        //     });
        // })
    });        
    </script>
@endpush
