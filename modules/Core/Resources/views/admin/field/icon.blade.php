<button type="button" class="btn btn-light" id="{{$id}}-iconpicker"></button>
<input type="hidden" name="{{$name}}" value="{{$value}}" id="{{$id}}">

@push('css')
    @loadcss(Module::asset('core:iconpicker/bootstrap-iconpicker.min.css'))
@endpush

@push('js')
    @loadjs(Module::asset('core:iconpicker/bootstrap-iconpicker.bundle.min.js'))
    <script type="text/javascript">
    $(function(){
        $("#{{$id}}-iconpicker").iconpicker({!!json_encode($options)!!}).on('change', function(e) {
            $("#{{$id}}").val(e.icon);
        });
    });
    </script>
@endpush
