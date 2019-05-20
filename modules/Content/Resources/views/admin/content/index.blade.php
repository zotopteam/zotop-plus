@extends('core::layouts.master')

@section('content')
@include('content::content.side')

<div class="main">
    <div class="main-header">
        @if($keywords = request('keywords'))
            <div class="main-back">
                <a href="{{route('content.content.index',$parent->id)}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
            </div>
            <div class="main-title mr-auto">
                {{$parent->title}}
            </div>                    
            <div class="main-title mr-auto">
                {{trans('core::master.searching', [$keywords])}}
            </div>        
        @else
        <div class="main-title mr-auto">
            {{$parent->title}}
        </div>
        <div class="main-action">
            @php($menus = Module::data('content::menu.create', get_defined_vars()))
            @if($menus->count()>1)
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-plus"></i> {{trans('content::content.create')}}
                </button>
                <div class="dropdown-menu dropdown-menu-primary">
                    @foreach($menus as $model)
                        <a class="dropdown-item" href="{{route('content.content.create',[$parent->id, $model->id])}}" title="{{$model->description}}" data-placement="left">
                            <i class="dropdown-item-icon {{$model->icon}} fa-fw"></i>
                            <b class="dropdown-item-text">{{$model->name}}</b>
                        </a>
                    @endforeach
                </div>
            </div>
            @else
                @foreach($menus as $model)
                    <a class="btn btn-primary" href="{{route('content.content.create',[$parent->id, $model->id])}}" title="{{$model->description}}">
                        <i class="btn-icon fa fa-plus fa-fw"></i>
                        <b class="btn-text">{{$model->name}}</b>
                    </a>
                @endforeach            
            @endif     
        </div>
        @endif
        <div class="main-action">
            {form route="content.content.index" class="form-inline form-search" method="get"}
                <div class="input-group">
                    <input name="keywords" value="{{$keywords}}" class="form-control" type="search" placeholder="{{trans('core::master.keywords.placeholder')}}" required="required" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i> </button>
                    </div>
                </div>
            {/form}
        </div>              
    </div>
    @if (empty($keywords))    
    <div class="main-header breadcrumb text-xs p-2 m-0">
        @if ($parent->id)
        <a href="{{route('content.content.index',[$parent->parent_id])}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-fw fa-arrow-up"></i>{{trans('content::content.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('content::content.up')}}</a>
        @endif
        <a class="breadcrumb-item" href="{{route('content.content.index')}}">{{trans('content::content.root')}}</a>
        @foreach($parents as $p)
        <a class="breadcrumb-item" href="{{route('content.content.index', $p->id)}}">{{$p->title}}</a> 
        @endforeach      
    </div>
    @endif    
    <div class="main-body scrollable">
        @if($contents->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            <table class="table table-select table-nowrap table-sortable table-hover">
                <thead>
                <tr>
                    <td class="drag"></td>
                    <td class="select">
                        <input type="checkbox" class="select-all text-muted">
                    </td>
                    <td colspan="3">{{trans('content::content.title.label')}}</td>
                    <td width="5%" class="text-center">{{trans('content::content.hits.label')}}</td>
                    <td width="5%">{{trans('content::content.user.label')}}</td>
                    <td></td>
                    <td width="5%">{{trans('content::content.status.label')}}</td>
                    <td width="10%"></td>
                </tr>
                </thead>
                <tbody>
                @foreach($contents as $content)
                    <tr data-id="{{$content->id}}" data-sort="{{$content->sort}}" data-stick="{{$content->stick}}" data-title="{{$content->title}}">
                        <td class="drag"></td>
                        <td class="select">
                            <input type="checkbox" name="ids[]" value="{{$content->id}}" class="select text-muted">
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
                                @if ($content->model->nestable)
                                    <a href="{{route('content.content.index', $content->id)}}">
                                        {{$content->title}}
                                    </a>
                                @else
                                    {{$content->title}}
                                @endif
                            </div>
                            <div class="manage">
                                @if ($manage = Filter::fire('content.manage', [], $content))
                                    @if ($flatten = array_slice($manage, 0, 6))
                                        @foreach ($flatten as $k=>$s)
                                        <div class="manage-item">
                                            <a href="{{$s.href ?? 'javascript:;'}}" class="{{$s.class ?? ''}}" {!!Html::attributes($s.attrs ?? [])!!}>
                                                <i class="{{$s.icon ?? ''}} fa-fw"></i> {{$s.text ?? ''}}
                                            </a>
                                        </div>
                                        @endforeach                                
                                    @endif
                                    
                                    @if ($dropdown = array_slice($manage, 6))
                                        <div class="manage-item dropdown">
                                            <a href="javascript:;" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-h fa-fw"></i><i class="fa fa-angle-down"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-primary">
                                                @foreach ($dropdown as $k=>$s)
                                                    <a href="{{$s.href ?? 'javascript:;'}}" class="dropdown-item {{$s.class ?? ''}}" {!!Html::attributes($s.attrs ?? [])!!}>
                                                        <i class="dropdown-item-icon {{$s.icon ?? ''}} fa-fw"></i>
                                                        <b class="dropdown-item-text">{{$s.text ?? ''}}</b>
                                                    </a>                                            
                                                @endforeach
                                            </div>                                                                                  
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td width="1%">
                            <a href="javascript:;" class="js-post" data-url="{{route('content.content.stick', [$content->id])}}">
                            @if ($content->stick)
                                <i class="fa fa-arrow-circle-up fa-2x text-primary" rel="stick-down" title="{{trans('content::content.unstick')}}" data-toggle="tooltip"></i>
                            @else
                                <i class="fa fa-arrow-circle-up fa-2x text-muted" rel="stick-up" title="{{trans('content::content.stick')}}" data-toggle="tooltip"></i>
                            @endif
                            </a>
                        </td>
                        <td class="text-center">{{$content->hits}}</td>
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
        <div class="main-action mr-auto">
            <button type="button" class="btn btn-outline-success js-select-operate disabled" disabled="disabled" data-operate="move">
                <i class="fa fa-arrows-alt fa-fw"></i> {{trans('core::master.move')}}
            </button>

            <div class="btn-group dropup">
                <button type="button" class="btn btn-outline-primary dropdown-toggle js-select-operate disabled" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h fa-fw"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-primary">    
                @foreach (\Modules\Content\Models\Content::status() as $k=>$s)
                @continue($k == 'future')
                <a href="javascript:;" class="dropdown-item js-select-operate disabled" disabled="disabled" data-operate="status" data-status="{{$k}}" data-url="{{route('content.content.status',[$k])}}">
                    <i class="{{$s.icon}} fa-fw"></i> {{$s.name}}
                </a>
                @endforeach
                <a href="javascript:;" class="dropdown-item js-select-operate disabled" disabled="disabled" data-operate="delete" data-confirm="{{trans('content::content.delete.confirm')}}">
                    <i class="fa fa-times fa-fw"></i> {{trans('content::content.destroy')}}
                </a>                
                </div>
            </div>
        </div>

        {{ $contents->appends($_GET)->links('core::pagination.default') }}
    </div>
</div>
@endsection
@push('css')
@endpush
@push('js')
<script type="text/javascript">
// post data
function postData(url, data, callback) {
    $.post(url, data, function(msg) {
        $.msg(msg);
        msg.state && callback(); 
    });    
}

// move dialog
function moveDialog(callback) {
    return $.dialog({
        id      : 'move-content',
        title   : '{{ trans('core::master.move') }}',
        url     : '{{ route('content.content.move')}}',
        width   : '75%',
        height  : '75%',
        padding : 0,
        ok      : function() {
            callback(this);
            return false;
        },
        cancel       : $.noop,
        oniframeload : function() {
            this.loading(false);
        },
        opener       : window
    }, true).loading(true);        
}

// move data
function moveData(id, parent_id, callback) {
    postData('{{route('content.content.move')}}', {id:id, parent_id:parent_id}, callback);
}


$(function(){

    // 单个取消置顶
    $('[rel=stick-down]').on('mouseover', function(){
        $(this).removeClass('fa-arrow-circle-up').addClass('fa-arrow-circle-down');
    }).on('mouseout', function(){
        $(this).removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-up');
    });

    // 单个置顶
    $('[rel=stick-up]').on('mouseover', function(){
        $(this).removeClass('text-muted').addClass('text-primary');
    }).on('mouseout', function(){
        $(this).removeClass('text-primary').addClass('text-muted');
    })        

    // 单个移动
    $(document).on('click', 'a.js-move', function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        moveDialog(function(dialog) {
            moveData(id, dialog.parent_id, function(){
                dialog.close().remove();
                location.reload();
            });
        })
        event.stopPropagation();
    });

    // 批量操作
    $(document).on('click','.js-select-operate', function(event) {
        event.preventDefault();
        var ids = $('table.table-select').data('selectTable').val();
        var operate = $(this).data('operate');

        // 移动
        if (operate == 'move') {
            moveDialog(function(dialog) {
                moveData(ids, dialog.parent_id, function(){
                    dialog.close().remove();
                    location.reload();
                });
            })        
        }

        // 状态
        if (operate == 'status') {
            postData($(this).data('url'), {id:ids});
        }

        // 永久删除
        if (operate == 'delete') {
            $.confirm($(this).data('confirm'), function(){
                postData('{{route('content.content.destroy')}}', {id:ids});
            })
        }

        event.stopPropagation();
    });


});
</script>
<script type="text/javascript">
$(function(){
    // 拖动停止更新当前的排序及当前数据之前的数据
    var dragstop = function(evt, ui, tr) {
        
        var oldindex = tr.data('originalIndex');
        var newindex = tr.prop('rowIndex');
        
        if(oldindex == newindex) { return; }

        var prev = ui.item.siblings('tr').eq(newindex-2); // 排到这一行之后
        var next = ui.item.siblings('tr').eq(newindex-1); // 排到这一行之前

        var id = tr.data('id');
        var newsort = ( newindex==1 || prev.data('sort') < next.data('sort') ) ? next.data('sort') + 1 : prev.data('sort');
        var newstick = ( newindex < oldindex ) ? next.data('stick') : prev.data('stick');

        //console.log(oldindex+'---'+newindex+'--'+ neworder +'--'+ newstick);

        $.post('{{route('content.content.sort', $parent->id)}}',{id:id, sort:newsort, stick:newstick}, function(data) {
            $.msg(data);
        },'json');      
    };  

    $("table.table-sortable").sortable({
        items: "tbody > tr",
        handle: "td.drag",
        axis: "y",
        placeholder:"ui-sortable-placeholder",
        helper: function(e,tr){
            tr.children().each(function(){
                $(this).width($(this).width());
            });
            return tr;
        },
        start:function (event,ui) {
            ui.placeholder.height(ui.helper[0].scrollHeight);
            ui.item.data('originalIndex', ui.item.prop('rowIndex'));
        },      
        stop:function(event,ui){
            dragstop.apply(this, Array.prototype.slice.call(arguments).concat(ui.item));
        }
    });
})
</script>
@endpush
