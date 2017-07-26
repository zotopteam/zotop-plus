@if($options['inline'])
<div class="input-datetime" id="datetimepicker-{{$name}}">
    {{Form::text($name,$value,$attrs)}}
</div>
@else
<div class="input-group input-group-merge input-datetime" id="datetimepicker-{{$name}}">    
    <span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
    {{Form::text($name,$value,$attrs)}}
</div>
@endif

@push('js')

    @once('FIELD_DATETIMEPICKER_INIT')
    <script type="text/javascript" src="{{Module::asset('core:datetimepicker/jquery.datetimepicker.js')}}"></script>
    <link rel="stylesheet" href="{{Module::asset('core:datetimepicker/jquery.datetimepicker.css')}}" rel="stylesheet">
    @endonce

    <script type="text/javascript">
    $(function(){
        $("#datetimepicker-{{$name}} input").datetimepicker({!!json_encode($options)!!});  
    });
    </script>

@endpush