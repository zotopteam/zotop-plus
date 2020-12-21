<div class="input-group" id="slug-{{$id}}">
    <input type="text" {{$attributes}}>
    <div class="input-group-append">
        <button type="button" class="btn btn-light btn-translate disabled" tabindex="-1">
            <i class="translate-icon fas fa-sync fa-fw mr-1"></i> {{$button}}
        </button>
    </div>
</div>

@push('js')
    {!! Module::load('translator:jquery.translate.js') !!}
    <script type="text/javascript">
        $(function () {
            $("#slug-{{$id}}").translate(@json($options));
        });
    </script>
@endpush
