@if($options['inline'])
<div class="input-datetime" id="datetimepicker-{{$name}}">
    {{Form::text($name,$value,$attrs)}}
</div>
@elseif($options['icon'])
<div class="input-group input-datetime" id="datetimepicker-{{$name}}">    
    <span class="input-group-addon"><i class="fa fa-calendar-alt fa-fw"></i></span>
    {{Form::text($name,$value,$attrs)}}
</div>
@else
    {{Form::text($name,$value,$attrs)}}
@endif

@push('js')

    @once('FIELD_DATETIMEPICKER_INIT')
    <script type="text/javascript" src="{{Module::asset('core:datetimepicker/jquery.datetimepicker.js')}}"></script>
    <link rel="stylesheet" href="{{Module::asset('core:datetimepicker/jquery.datetimepicker.css')}}" rel="stylesheet">
    @endonce
    <script type="text/javascript">
    $(function(){
        $("#{{$id}}").datetimepicker({!!json_encode($options)!!});  
    });
    </script>
@endpush
