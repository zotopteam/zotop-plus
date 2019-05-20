<div id="md-editor-{{$name}}">
    <textarea name="{{$name}}" style="display:none;">{{$value}}</textarea>
</div>
@push('css')
    @loadcss(Module::asset('core:editormd/css/editormd.min.css'))
@endpush
@push('js')
    @loadjs(Module::asset('core:editormd/editormd.min.js'))
    @if(! App::isLocale('zh-Hans'))
    @loadjs(Module::asset('core:editormd/languages/'.App::getLocale().'.js'))
    @endif
    
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
