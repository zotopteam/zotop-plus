<input {{$attributes}}/>

@push('js')
    {!! Module::load('core:laydate/laydate.js') !!}
    <script type="text/javascript">
        laydate.render(@json($options));
    </script>
@endpush
