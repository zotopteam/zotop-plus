@extends('layouts.dialog')

@section('content')

<div class="main">
    <div class="main-header">
        @if($keywords = request('keywords'))
            <div class="main-back">
                <a href="{{route('content.content.sort',[$parent->id, 'id'=>$sort->id])}}">
                    <i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b>
                </a>
            </div>           
            <div class="main-title mr-auto">
                {{$parent->title}}
            </div>                          
            <div class="main-title mx-auto">
                {{trans('master.searching', [$keywords])}}
            </div>        
        @else
        <div class="main-title">
            {{$parent->title}}
        </div>
        @if ($parent->id)
        <div class="main-breadcrumb breadcrumb text-xs p-1 px-2 m-0 mx-2">
            <span class="breadcrumb-item">{{trans('content::content.root')}}</span>
            @foreach($path as $p)
            <span class="breadcrumb-item">{{$p->title}}</span> 
            @endforeach              
        </div>
        @endif        
        @endif
        <div class="main-action ml-auto">
            {form route="['content.content.sort', $parent->id]" class="form-inline form-search" method="get"}
                <input type="hidden" name="id" value="{{$sort->id}}">
                <div class="input-group">
                    <input name="keywords" value="{{$keywords}}" class="form-control" type="search" placeholder="{{trans('master.keywords.placeholder')}}" required="required" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i></button>
                    </div>
                </div>
            {/form}
        </div>
        <div class="main-action">
            <a href="javascript:location.reload()" class="btn btn-light" title="{{trans('master.refresh')}}">
                <span class="fa fa-sync"></span>
            </a>
        </div>                     
    </div>
    <div class="main-body scrollable">
        @if($contents->count() == 0)
            <div class="nodata">{{trans('master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover">
                <thead>
                <tr>
                    <td width="1%"></td>
                    <td colspan="3">{{trans('content::content.title.label')}}</td>
                    <td width="5%">{{trans('content::content.user.label')}}</td>
                    <td></td>
                    <td width="5%">{{trans('content::content.status.label')}}</td>
                    <td width="10%"></td>
                </tr>
                </thead>
                <tbody>
                @foreach($contents as $content)
                    <tr data-id="{{$content->id}}" data-sort="{{$content->sort}}" data-stick="{{$content->stick}}">
                        <td class="text-center">
                            <i class="select-icon fa fa-check-square fa-2x"></i>
                        </td>
                        @if ($content->image)
                        <td class="text-center px-2" width="1%">
                            <a href="javascript:;" class="js-image" data-url="{{$content->image}}" data-title="{{$content->title}}">
                                <div class="icon icon-md">
                                    <img src="{{$content->image}}">
                                </div>
                            </a>
                        </td>
                        @else
                        <td class="p-0"></td>
                        @endif
                        <td class="px-2">
                            <div class="title">
                                <i class="{{$content->model->icon}} fa-fw text-warning" title="{{$content->model->name}}" data-toggle="tooltip"></i>
                                {{$content->title}}
                            </div>
                        </td>
                        <td width="1%">
                           @if ($content->stick)
                                <i class="fa fa-arrow-circle-up fa-2x text-primary"></i>
                            @endif                        
                        </td>
                        <td><strong>{{$content->user->username}}</strong></td>
                        <td></td>
                        <td>
                            <i class="{{$content->status_icon}} fa-fw fa-2x" title="{{$content->status_name}}" data-toggle="tooltip"></i>
                        </td>
                        <td>
                            @if (in_array($content->status,['publish']))
                            <div>{{trans('content::content.publish_at.label')}}</div>
                            <div>{{$content->publish_at}}</div>
                            @elseif (in_array($content->status,['future']))
                            <div>{{trans('content::content.status.future')}}</div>
                            <div>{{$content->publish_at}}</div>                            
                            @else
                            <div>{{trans('content::content.updated_at.label')}}</div>
                            <div>{{$content->updated_at}}</div>
                            @endif                            
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mx-auto">
        {{ $contents->appends($_GET)->links() }}
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
$(function(){
    var target = $('table>tbody>tr');

    // 单击选择
    target.on('click', function(event) {
        if($(this).hasClass('selected')) {
            $(this).removeClass('selected'); 
        } else {
            $(this).addClass('selected').siblings(".selected").removeClass('selected'); //单选
        }
    });

    // statusbar
    currentDialog.statusbar('{{trans('content::content.sort.help', [str_limit($sort->title,80)])}}');

    // 确定按钮回调
    currentDialog.callbacks['ok'] = function () {

        var selected = target.filter('.selected');

        if (selected.length) {

            var newsort  = selected.data('sort') + 1;
            var newstick = selected.data('stick');
            
            $.post('{{route('content.content.sort', $parent->id)}}',{id:{{$sort->id}}, sort:newsort, stick:newstick}, function(msg) {
                $.msg(msg);

                if (msg.type == 'success') {
                    currentDialog.opener.location.reload();
                    currentDialog.close();
                }

            },'json');
        } else {
            $.error('{{ trans('master.select.min', [1]) }}');
        }

        return false;
    }      
})




   
</script>
@endpush
