<div id="tinymce-editor-{{$name}}">
    <textarea name="{{$name}}" id="{{$id}}" style="visibility:hidden;">{{$value}}</textarea>
</div>

@push('js')
    @loadjs(Module::asset('tinymce:tinymce/tinymce.min.js'))
    @loadjs(Module::asset('tinymce:tinymce/jquery.tinymce.min.js'))

    <script type="text/javascript">
    $(function(){
        $('#{{$id}}').tinymce(@json($options));
    });        
    </script>
@endpush
