<div class="input-group input-title-style" id="content-title-{{$id}}">

    <input type="text" {{$attributes}}>
    <input type="hidden" name="{{$styleName}}" value="{{$styleValue}}">

    <div class="input-group-append">
        <button type="button" class="btn btn-light btn-bold" tabindex="-1">
            <i class="fa fa-bold fa-fw"></i>
        </button>
        <button type="button" class="btn btn-light btn-color" tabindex="-1">
            <i class="fa fa-palette fa-fw"></i>
        </button>
    </div>
</div>

@push('css')
    {!! Module::load('core:spectrum/spectrum.css') !!}
    {!! Module::load('core:spectrum/spectrum.zotop.css') !!}
@endpush

@push('js')
    {!! Module::load('core:spectrum/spectrum.js') !!}
    {!! Module::load('content:js/input_style.js') !!}

    <script type="text/javascript">
        $(function () {
            $('#content-title-{{$id}}').input_style(@json($options));
        });
    </script>
@endpush
