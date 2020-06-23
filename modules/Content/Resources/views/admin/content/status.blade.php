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

            @foreach (\Modules\Content\Models\Content::status() as $k=>$s)
            @continue($k == $status || $k == 'future')
            <a href="javascript:;" class="btn btn-outline-{{$s.color}} checkable-operator disabled" disabled="disabled"
                data-operate="status" data-status="{{$k}}" data-url="{{route('content.content.status',[$k])}}">
                <i class="{{$s.icon}} fa-fw"></i> {{$s.text}}
            </a>
            @endforeach
            @if ($status == 'trash')
            <a href="javascript:;" class="btn btn-danger checkable-operator disabled" disabled="disabled"
                data-operate="delete" data-url="{{route('content.content.destroy')}}"
                data-confirm="{{trans('content::content.delete.confirm')}}"
                data-title="{{trans('content::content.destroy')}}">
                <i class="fa fa-times fa-fw"></i> {{trans('content::content.destroy')}}
            </a>
            @endif
        </div>

    </div>
    <div class="main-header bg-light text-sm py-1 px-3 ">
        <input type="checkbox" class="checkable-all mr-auto" />
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
        <x-content-admin-list :list="$contents" :view="$show" :nestable="false" />
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
