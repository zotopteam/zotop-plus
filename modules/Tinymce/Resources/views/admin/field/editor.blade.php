<div id="tinymce-editor-{{$name}}">
    <textarea name="{{$name}}" id="{{$id}}" style="visibility:hidden;">{{$value}}</textarea>
</div>

@push('js')
    {!! Module::load('tinymce:tinymce/tinymce.min.js') !!}
    {!! Module::load('tinymce:tinymce/jquery.tinymce.min.js') !!}
    {!! Module::load('tinymce:field_editor.js') !!}
    <script type="text/javascript">
        $(function(){
            field_editor('#{{$id}}', @json($options)); 
        });
    </script>
@endpush
