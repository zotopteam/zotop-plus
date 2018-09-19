<div id="md-editor-{{$name}}">
    <textarea name="{{$name}}" style="display:none;">{{$value}}</textarea>
</div>
@push('css')
    @once('editormd_css')
    <link rel="stylesheet" type="text/css" href="{{Module::asset('core:editormd/css/editormd.min.css')}}">
    @endonce
@endpush
@push('js')
    @once('editormd_js')
    <script type="text/javascript" src="{{Module::asset('core:editormd/editormd.min.js')}}"></script>
    @if(!App::isLocale('zh-Hans'))
    <script type="text/javascript" src="{{Module::asset('core:editormd/languages/'.App::getLocale().'.js')}}"></script>
    @endif
    @endonce

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
