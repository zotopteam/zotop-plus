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
            {form route="['content.content.index',$parent->id]" class="form-inline form-search" method="get"}
                <div class="input-group">
                    <input name="keywords" value="{{$keywords}}" class="form-control" type="search" placeholder="{{trans('core::master.keywords.placeholder')}}" required="required" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i> </button>
                    </div>
                </div>
            {/form}
        </div>              
    </div>
    <div class="main-body scrollable">
        @if($contents->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-sortable table-hover">
                <thead>
                <tr>
                    <td class="drag"></td>
                    <td colspan="2">{{trans('content::content.title.label')}}</td>
                    <td width="5%">{{trans('content::content.user.label')}}</td>
                    <td></td>
                    <td width="5%">{{trans('content::content.status.label')}}</td>
                    <td width="10%"></td>
                </tr>
                </thead>
                <tbody>
                @foreach($contents as $content)
                    <tr data-id="{{$content->id}}" data-sort="{{$content->sort}}" data-stick="{{$content->stick}}">
                        <td class="drag"></td>
                        <td class="text-center px-2" width="5%">
                            @if ($content->image)
                            <a href="javascript:;" class="js-image" data-url="{{$content->image}}" data-title="{{$content->title}}">
                                <div class="icon icon-md">
                                    <img src="{{$content->image}}">
                                </div>
                            </a>
                            @else
                            <i class="{{$content->model->icon}} fa-md text-warning"></i>
                            @endif
                        </td>
                        <td class="px-2">
                            <div class="title text-lg">
                                @if ($content->model->nestable)
                                    <a href="{{route('content.content.index', $content->id)}}">{{$content->title}}</a>
                                @else
                                    {{$content->title}}
                                @endif
                            </div>
                            <div class="manage">
                                @foreach(Filter::fire('content.manage', [], $content) as $s)
                                <a href="{{$s.herf ?? 'javascript:;'}}" class="manage-item {{$s.class ?? ''}}" {!!Html::attributes(array_except($s,['icon','text','href','class']))!!}>
                                    <i class="{{$s.icon ?? ''}} fa-fw"></i> {{$s.text}}
                                </a>
                                @endforeach
                            </div>
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
        <div class="footer-text mr-auto">
            {{trans('content::content.description')}}
        </div>

        {{ $contents->links('core::pagination.default') }}
    </div>
</div>
@endsection

@push('js')
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
