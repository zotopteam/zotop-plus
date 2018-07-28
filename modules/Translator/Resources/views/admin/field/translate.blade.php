<div class="input-group input-datetime" id="translate-{{$id}}">    
    {{Form::text($name,$value,$attrs)}}
    <div class="input-group-append">
        <span class="input-group-text btn btn-translate disabled">
            <i class="translate-icon fas fa-sync fa-fw mr-1"></i> {{$button}}
        </span>
    </div>
</div>

@push('js')

    @once('FIELD_TRANSLATE_INIT')
    <script type="text/javascript" src="{{Module::asset('translator:jquery.translate.js')}}"></script>
    @endonce
    <script type="text/javascript">
    $(function(){
        $("#translate-{{$id}}").translate({!!json_encode($options)!!});  
    });
    </script>
@endpush
