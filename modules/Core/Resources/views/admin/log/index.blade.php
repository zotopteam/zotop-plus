@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            {form route="core.log.index" class="form-inline form-search" method="get"}
                <div class="input-group">
                    <input name="keywords" value="{{request('keywords')}}" class="form-control border-primary" type="search" placeholder="{{trans('master.keywords.placeholder')}}" required="required" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i> </button>
                        @if (request('keywords'))
                            <a href="{{route('core.log.index')}}" class="btn btn-danger"> <i class="fa fa-times"></i> </a>
                        @endif
                    </div>
                </div>
            {/form}
        </div>          
    </div>
    <div class="main-body scrollable">

        @if ($logs->count() == 0)
            <div class="nodata">{{trans('master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover">
                <thead>
                    <tr>
                        <td class="text-center" width="1%">{{trans('core::log.id.label')}}</td>
                        <td>{{trans('core::log.user_name.label')}}</td>
                        <td>{{trans('core::log.url.label')}}</td>
                        <td>{{trans('core::log.content.label')}}</td>
                        <td width="5%">{{trans('core::log.user_ip.label')}}</td>
                        <td width="5%">{{trans('core::log.created_at.label')}}</td>
                        <td width="5%"></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                    <tr>
                        <td class="text-center">{{$log->id}}</td>
                        <td>
                            <span data-toggle="tooltip" title="{{trans('core::log.user_id.label')}}: {{$log->user_id}}">{{$log->user->username}}</span>
                        </td>
                        <td>
                            <div class="m-0 p-0">
                                <span data-toggle="tooltip" title="{{trans('core::log.module.label')}}">{{$log->module}}</span>
                                <span class="text-black-50">></span>
                                <span data-toggle="tooltip" title="{{trans('core::log.controller.label')}}">{{$log->controller}}</span>
                                <span class="text-black-50">></span>
                                <span data-toggle="tooltip" title="{{trans('core::log.action.label')}}">{{$log->action}}</span>
                            </div>
                            <div class="text-wrap text-break text-xs">{{$log->url}}</div>
                        </td>
                        <td>
                            <div class="text-{{$log->type}}">
                            {{$log->content}}
                            </div>
                        </td>
                        <td>{{$log->user_ip}}</td>
                        <td>{{$log->created_at}}</td>
                        <td class="manage">
                            @if ($request = $log->request)
                                <a href="javascript:;" class="manage-item js-alert-inner">
                                    <span class="js-alert-inner-title">
                                        {{trans('core::log.request.label')}}
                                    </span>
                                    <span class="js-alert-inner-content d-none">
                                        @json($request)
                                    </span>
                                </a>
                            @endif

                            @if ($resposne = $log->resposne)
                                <a href="javascript:;" class="manage-item js-alert-inner">
                                    <span class="js-alert-inner-title">
                                        {{trans('core::log.resposne.label')}}
                                    </span>
                                    <span class="js-alert-inner-content d-none">
                                        <table class="table table-nowrap table-hover">
                                            <tbody>
                                                @foreach ($resposne as $key=>$val)
                                                <tr>
                                                    <td width="10%">{{$key}}</td>
                                                    <td class="text-wrap text-break">{!! $val !!}</td>  
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </span>
                                </a>
                            @endif                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="main-footer">
        <div class="footer-text mr-auto">
        </div>
        {{ $logs->appends($_GET)->links() }}
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')
<script type="text/javascript">
    $(function(){
        $(document).on('click', 'a.js-alert-inner', function(){
            var title   = $(this).find('.js-alert-inner-title').text();
            var content = $(this).find('.js-alert-inner-content').html();
                content = JSON.stringify(JSON.parse(content), null, 2);
            $.dialog({
                title   : title,
                content : function() {
                    return '<pre class="bg-dark text-white-50 text-break full-height scrollable p-3 inner-data"></pre>';
                },
                onshow  : function() {
                    var self = this;
                    setTimeout(function () {
                        self._$('content').find('.inner-data').html(content);
                        self.loading(false);
                    }, 500);
                },
                width   : '80%',
                height  : '80%',
                ok      : true
            },true).loading(true);
        })
    })
</script>
@endpush
