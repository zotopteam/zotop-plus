@extends('layouts.master')

@section('content')
<x-sidebar data="media::sidebar" :header="trans('media::media.title')" :active="$type" class="fw-10" />
<div class="main checkable">
    <div class="main-header">

        <div class="main-title mr-auto">
            {{request('keywords') ? trans('master.search.results') : $title}}
        </div>

        <div class="main-action">
            <x-search />
            @if (!request('keywords'))
            <x-upload-chunk :type="$type" />
            @endif
            <a href="javascript:;" class="btn btn-success checkable-operator disabled" data-operate="move"
                data-url="{{route('media.move')}}" data-title="{{trans('master.move')}}">
                <i class="fa fa-arrows-alt fa-fw"></i> {{trans('master.move')}}
            </a>
            <a href="javascript:;" class="btn btn-danger checkable-operator disabled" data-operate="delete"
                data-confirm="{{trans('master.delete.confirm')}}" data-url="{{route('media.destroy')}}">
                <i class="fa fa-times fa-fw"></i> {{trans('master.delete')}}
            </a>
        </div>
    </div>
    <div class="main-header bg-light text-xs p-1">
        <div class="d-flex ml-auto px-2">
            <input type="checkbox" class="checkable-all fs-1" />
        </div>
    </div>
    <div class="main-body scrollable" id="file-upload-dragdrop">
        <x-media-list :list="$media_list" class="grid-sm grid-gap-xs" />
    </div><!-- main-body -->
    @if ($media_list->hasPages())
    <div class="main-footer">
        <div class="ml-auto">
            {{ $media_list->withQueryString()->links() }}
        </div>
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
                        $.post(operator.data('url'), {id: ids, folder_id: dialog.folder_id}, function (msg) {
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
