<div class="input-group" id="translate-{{$id}}">    
    {{Form::text($name,$value,$attrs)}}
    <div class="input-group-append">
        <span class="input-group-text btn btn-translate disabled">
            <i class="translate-icon fas fa-sync fa-fw mr-1"></i> {{$button}}
        </span>
    </div>
</div>

@push('js')
    @loadjs(Module::asset('translator:jquery.translate.js'))
    <script type="text/javascript">
    $(function(){
        $("#translate-{{$id}}").translate(@json($options));  
    });
    </script>
@endpush
