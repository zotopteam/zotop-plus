<button type="button" class="btn btn-light" id="{{$id}}-iconpicker"></button>
<input type="hidden" name="{{$name}}" value="{{$value}}" id="{{$id}}">

@push('css')
    @once('FIELD_ICONPICKER_INIT_CSS')
    <link rel="stylesheet" href="{{Module::asset('core:iconpicker/bootstrap-iconpicker.min.css')}}" rel="stylesheet">    
    @endonce
@endpush

@push('js')

    @once('FIELD_ICONPICKER_INIT_JS')
    <script type="text/javascript" src="{{Module::asset('core:iconpicker/bootstrap-iconpicker.bundle.min.js')}}"></script>
    @endonce
    <script type="text/javascript">
    $(function(){
        $("#{{$id}}-iconpicker").iconpicker({!!json_encode($options)!!}).on('change', function(e) {
            $("#{{$id}}").val(e.icon);
        });
    });
    </script>
@endpush
