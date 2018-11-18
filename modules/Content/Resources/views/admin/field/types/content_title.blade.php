<div class="input-group input-title-style" id="content-title-{{$id}}">    
    {{Form::text($name, $value, $attrs)}}
    {{Form::hidden($name.'_style')}}
    <div class="input-group-append">
        <button type="button" class="btn btn-light btn-bold"><i class="fa fa-bold fa-fw"></i></button>
        <button type="button" class="btn btn-light btn-color"><i class="fa fa-palette fa-fw"></i></button>
    </div>
</div>

@push('css')
    @once('SPECTURM_CSS_INIT')
    <link rel="stylesheet" href="{{Module::asset('core:spectrum/spectrum.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Module::asset('core:spectrum/spectrum.zotop.css')}}" rel="stylesheet">
    @endonce
@endpush

@push('js')

    @once('SPECTURM_JS_INIT')
    <script type="text/javascript" src="{{Module::asset('core:spectrum/spectrum.js')}}"></script>
    <script type="text/javascript" src="{{Module::asset('content:js/input_style.js')}}"></script>
    @endonce
    <script type="text/javascript">
    $(function(){
         $('#content-title-{{$id}}').input_style(@json($options));
    });
    </script>
@endpush
