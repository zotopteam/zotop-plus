<div class="input-group input-title-style" id="content-title-{{$id}}">    
    {{Form::text($name, $value, $attrs)}}
    {{Form::hidden($name.'_style')}}
    <div class="input-group-append">
        <button type="button" class="btn btn-light btn-bold" tabindex="-1"><i class="fa fa-bold fa-fw"></i></button>
        <button type="button" class="btn btn-light btn-color" tabindex="-1"><i class="fa fa-palette fa-fw"></i></button>
    </div>
</div>

@push('css')
    @loadcss(Module::asset('core:spectrum/spectrum.css'))
    @loadcss(Module::asset('core:spectrum/spectrum.zotop.css'))    
@endpush

@push('js')
    @loadjs(Module::asset('core:spectrum/spectrum.js'))
    @loadjs(Module::asset('content:js/input_style.js'))
    <script type="text/javascript">
    $(function(){
         $('#content-title-{{$id}}').input_style(@json($options));
    });
    </script>
@endpush
