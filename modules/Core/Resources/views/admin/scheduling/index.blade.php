@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="#" class="btn btn-primary d-none"> <i class="fa fa-plus"></i> Button </a>
        </div>           
    </div>
    <div class="main-body scrollabel">
        @if($tasks->count() == 0)
            <div class="nodata">{{trans('master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover" >
                <thead>
                <tr>
                    <th class="text-center" width="1%">#</th>
                    <th class="text-center" width="5%">{{trans('core::scheduling.task.type')}}</th>
                    <th>{{trans('core::scheduling.task.cmd')}}</th>
                    <th>{{trans('core::scheduling.task.expression')}}</th>
                    <th>{{trans('core::scheduling.task.nextrun_at')}}</th>
                    <th>{{trans('core::scheduling.task.timezone')}}</th>
                    <th>{{trans('core::scheduling.task.environments')}}</th>
                    <td class="text-center">{{trans('core::scheduling.task.run_in_background')}}</td>
                    <th>{{trans('core::scheduling.task.expires_at')}}</th>
                    <th width="15%">{{trans('core::scheduling.task.description')}}</th>
                    <td class="text-center">{{trans('core::scheduling.task.run')}}</td>
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td class="text-center">
                            {{$task->index + 1}}
                        </td>
                        <td class="text-center">
                            <label class="badge badge-primary">{{$task->type}}</label>
                        </td>
                        <td>
                            {{$task->cmd}}
                        </td>
                        <td>
                            <label class="badge badge-success">{{$task->expression}}</label>
                        </td>
                        <td>{{$task->nextRunDate()->format('Y-m-d H:i:s')}}</td>
                        <td>{{$task->timezone}}</td>
                        <td>
                            @if ($task->environments)                   
                                @foreach ($task->environments as $env)
                                    <label class="badge badge-info">{{$env}}</label>
                                @endforeach
                            @else
                                --
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($task->runInBackground)
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            @else
                                <i class="fas fa-times-circle fa-2x text-muted"></i>
                            @endif
                        </td>
                        <td>{{$task->expiresAt}} {{trans('core::scheduling.task.expires_unit')}}</td>
                        <td>{{$task->description}}</td>
                        <td class="text-center">
                            <a href="{{route('core.scheduling.run',[$task->index])}}" class="js-run">
                                <i class="fa fa-play-circle fa-2x"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="text-xs mr-auto">
            {{trans('core::scheduling.description')}}
        </div>
        <div class="text-xs">
        @if (stristr(PHP_OS, 'LINUX'))
            Linux Cron：* * * * * cd {{base_path()}} && php artisan schedule:run >> /dev/null 2>&1
        @endif
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){
        $(document).on('click', 'a.js-run',function(event){
            event.preventDefault();

            var icon = $(this).find('.fa');
            var href = $(this).attr('href');
            icon.addClass('fa-loading');

            $.post(href, function(result) {
                icon.removeClass('fa-loading');
                if (result) {
                    $.dialog({
                        skin    : 'ui-cmd',
                        width   : '80%',
                        height  : '60%',
                        title   : '{{trans('core::scheduling.task.run_result')}}',
                        content : '<pr class="cmd-output">'+result+'</pre>',
                        ok      : true
                    }, true);
                }     
            });

            event.stopPropagation();
        });        
    });
</script>
@endpush
