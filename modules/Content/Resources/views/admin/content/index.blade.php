@extends('layouts.master')

@section('content')
@include('content::content.side')
<div class="main flex-shrink-1">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <x-search />

            @if($creates->count() > 1)
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-plus"></i> {{trans('content::content.create')}}
                </button>
                <div class="dropdown-menu dropdown-menu-primary">
                    @foreach($creates as $model)
                    <a class="dropdown-item" href="{{route('content.content.create',[$id, $model->id])}}"
                        title="{{$model->description}}" data-placement="left">
                        <i class="dropdown-item-icon {{$model->icon}} fa-fw"></i>
                        <b class="dropdown-item-text">{{$model->name}}</b>
                    </a>
                    @endforeach
                </div>
            </div>
            @else
            @foreach($creates as $model)
            <a class="btn btn-primary" href="{{route('content.content.create',[$id, $model->id])}}"
                title="{{$model->description}}">
                <i class="btn-icon fa fa-plus fa-fw"></i>
                <b class="btn-text">{{$model->name}}</b>
            </a>
            @endforeach
            @endif

            <button type="button" class="btn btn-outline-success checkable-operator disabled" disabled="disabled"
                data-operate="move" data-url="{{route('content.content.move')}}">
                <i class="fa fa-arrows-alt fa-fw"></i> {{trans('master.move')}}
            </button>

            <div class="btn-group dropdown">
                <button type="button" class="btn btn-outline-primary dropdown-toggle checkable-operator disabled"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h fa-fw"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-primary">
                    @foreach (\Modules\Content\Models\Content::status() as $k=>$s)
                    @continue($k == 'future')
                    <a href="javascript:;" class="dropdown-item checkable-operator disabled" disabled="disabled"
                        data-operate="status" data-status="{{$k}}" data-url="{{route('content.content.status',[$k])}}">
                        <i class="{{$s.icon}} fa-fw"></i> {{$s.text}}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
    <div class="main-header bg-light text-sm py-1 px-3 ">
        <x-content-admin-breadcrumb :content="$content" class="mr-auto text-xs scale-n2 scale-left p-0" />
        <input type="checkbox" class="checkable-all mr-3" />
        @if ($show == 'grid')
        <a href="{{Request::fullUrlWithQuery(['show'=>'list'])}}" class="fw-1">
            <i class="fa fa-bars"></i>
        </a>
        @endif
        @if ($show == 'list')
        <a href="{{Request::fullUrlWithQuery(['show'=>'grid'])}}" class="fw-1">
            <i class="fa fa-th"></i>
        </a>
        @endif
    </div>
    <div class="main-body scrollable">
        <x-content-admin-list :list="$contents" :view="$show" :sortable="route('content.content.sort', $id)" />
    </div><!-- main-body -->
    @if ($contents->hasPages())
    <div class="main-footer">
        {{ $contents->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@push('js')
<script type="text/javascript">
    // 多选操作
    $(function(){      

        $(document).on('click','.checkable-operator', function(event) {
            
            var ids      = $('.checkable').data('checkable').val();
            var operator = $(this);
            
            // 多选移动
            if (operator.data('operate') == 'move') {
                var dialog = $.dialog({
                    title : operator.data('title'),
                    url : operator.data('url'),
                    width : '85%',
                    height : '70%',
                    ok : function() {
                        $.post(operator.data('url'), {id: ids, move_to: dialog.move_to}, function (msg) {
                            $.msg(msg);
                            if (msg.type == 'success') {
                                dialog.close().remove();
                                location.reload();
                            }
                        })                            
                        return false;
                    },
                    cancel : $.noop
                }, true).loading(true);
            }
            // 多选删除
            if (operator.data('operate') == 'status') {
                $.post(operator.data('url'), {id: ids}, function(msg) {
                    $.msg(msg);
                });
            }
            // 多选删除
            if (operator.data('operate') == 'delete') {
                $.confirm(operator.data('confirm'), function() {
                    $.post(operator.data('url'), {id: ids}, function(msg) {
                        $.msg(msg);
                    });                    
                })
            }                
        });
              
    });      
</script>
@endpush
