@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        @if ($block->data)        
        <div class="main-back">
            <a href="{{route('block.index',$block->category_id)}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        @endif
        <div class="main-title mr-auto">
            {{$block->name}} - {{$title}}
        </div>
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary js-open" data-url="{{route('block.datalist.create', $block->id)}}" data-width="800" data-height="400">
                <i class="fa fa-plus fa-fw"></i> {{trans('core::master.create')}}
            </a>
            @if ($block->data)   
            <a href="javascript:;" class="btn btn-info js-open" data-url="{{route('block.preview', $block->id)}}" data-width="80%" data-height="60%">
                <i class="fa fa-eye fa-fw"></i> {{trans('block::block.preview')}}
            </a>
            @endif
            @if ($history = \Modules\Block\Models\Datalist::history($block->id)->count())    
            <a class="btn btn-info" href="{{route('block.datalist.history', $block->id)}}">
                <i class="fa fa-history fa-fw"></i> {{trans('block::datalist.history')}} <span class="badge badge-pill badge-light">{{$history}}</span>
            </a>
            @endif          
            <a class="btn btn-info" href="{{route('block.edit', $block->id)}}">
                <i class="fa fa-cog fa-fw"></i> {{trans('block::block.setting')}}
            </a>            
        </div>
    </div>
    
    <div class="main-body scrollable">

        <table class="table table-nowrap table-sortable table-hover">
            <thead>
            <tr>
                <td class="drag"></td>
                <td width="2%">{{trans('block::datalist.row')}}</td>
                <td>{{trans('block::datalist.title')}}</td>
                <td width="10%" class="text-center"></td>
                <td width="10%">{{trans('core::master.lastmodify')}}</td>
            </tr>
            </thead>
            <tbody>
                @foreach (\Modules\Block\Models\Datalist::publish($block->id) as $i=>$datalist)

                <tr data-id="{{$datalist->id}}" data-sort="{{$datalist->sort}}" data-stick="{{$datalist->stick}}">
                    <td class="drag"></td>
                    <td>{{$i+1}}</td>
                    <td valign="middle">
                        @if ($datalist->image_preview)
                            <a href="javascript:;" class="js-image" data-url="{{preview($datalist->image_preview)}}" data-title="{{$datalist->title}}">
                                <div class="image-preview bg-image-preview text-center float-left mr-3">
                                    <img src="{{preview($datalist->image_preview, 64, 64)}}">
                                </div>
                            </a>
                        @endif
                        <div class="title text-lg">
                            {{$datalist->title}}
                        </div>
                        <div class="manage">
                            @if ($datalist->stick)
                            <a class="manage-item js-confirm" href="{{route('block.datalist.stick', [$datalist->id, 0])}}">
                                <i class="fas fa-arrow-circle-down"></i> {{trans('block::datalist.stick.off')}}
                            </a>
                            @else
                            <a class="manage-item js-confirm" href="{{route('block.datalist.stick', [$datalist->id, 1])}}">
                                <i class="fas fa-arrow-circle-up"></i> {{trans('block::datalist.stick.on')}}
                            </a>
                            @endif                           
                            <a class="manage-item js-open" href="javascript:;"  data-url="{{route('block.datalist.edit', $datalist->id)}}" data-width="800" data-height="400">
                                <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                            </a>
                            <a class="manage-item js-delete" href="javascript:;" data-url="{{route('block.datalist.destroy', $datalist->id)}}">
                                <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                            </a>
                        </div>
                    </td>
                    <td class="text-center">
                        @if ($datalist->stick)
                        <i class="fas fa-arrow-circle-up fa-2x text-success" title="{{trans('block::datalist.stick.on')}}" data-toggle="tooltip"></i>
                        @endif
                    </td>
                    <td>
                        <b>{{$datalist->user->username}}</b>
                        <div class="text-sm">{{$datalist->updated_at}}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if (! $block->data)
        <div class="nodata">{{trans('core::master.nodata')}}</div>
        @endif


    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {{$block->description}}
        </div>
        @if ($block->rows)        
        <div class="ml-auto text-nowrap">
            {{trans('block::block.rows')}} : {{$block->rows}}
        </div>
        @endif       
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

            $.post('{{route('block.datalist.sort', $block->id)}}',{id:id,sort:newsort,stick:newstick},function(data){
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
                ui.item.data('originalIndex', ui.item.prop('rowIndex'));
            },      
            stop:function(event,ui){
                dragstop.apply(this, Array.prototype.slice.call(arguments).concat(ui.item));
            }
        });
    })
</script>
@endpush
