<div id="tinymce-editor-{{$name}}">
    <textarea name="{{$name}}" id="{{$id}}" style="visibility:hidden;">{{$value}}</textarea>
</div>

@push('js')
    @once('tinymce_js')
    <script type="text/javascript" src="{{Module::asset('tinymce:tinymce/tinymce.min.js')}}"></script>
    <script type="text/javascript" src="{{Module::asset('tinymce:tinymce/jquery.tinymce.min.js')}}"></script>
    @endonce

    <script type="text/javascript">
    $(function(){
        $('#{{$id}}').tinymce({!! json_encode($options) !!});
    });        
    </script>
@endpush
