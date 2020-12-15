<button type="button" class="btn btn-light" id="{{$id}}-iconpicker"></button>
<input type="hidden" name="{{$name}}" value="{{$value}}" id="{{$id}}">

@push('css')
    {!! Module::load('core:iconpicker/bootstrap-iconpicker.min.css') !!}
@endpush

@push('js')
    {!! Module::load('core:iconpicker/bootstrap-iconpicker.bundle.min.js') !!}
    <script type="text/javascript">
        $(function () {
            $("#{{$id}}-iconpicker").iconpicker({!!json_encode($options)!!}).on('change', function (e) {
                $("#{{$id}}").val(e.icon);
            });
        });
    </script>
@endpush
