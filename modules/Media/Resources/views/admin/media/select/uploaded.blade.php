@extends('layouts.dialog')

@section('content')
<x-sidebar :data="['core::field.upload.tools', Request::all()]" class="w-auto" />
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <x-upload-chunk />
            <a href="javascript:location.reload();" class="btn btn-light" title="{{trans('master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>
        </div>
    </div>
    <div class="main-body scrollable ias-scrollable" id="file-upload-dragdrop">
        <x-media-list :list="$files" class="grid-sm grid-gap-sm" :checkable="request('type')"
            :mutiple="request('mutiple')" action="view,rename,delete" />
    </div><!-- main-body -->
    @if ($files->hasPages())
    <div class="main-footer text-sm p-1">
        {{ $files->appends($params)->links('pagination.ias') }}
    </div>
    @endif
</div>
@endsection


@push('js')
<script type="text/javascript">
    // 确定按钮回调
    currentDialog.callbacks['ok'] = function () {
        var selected  = new Array();

        $('.file-item').find('input.checkable-control').each(function() {
            if ($(this).prop('checked')) {
                selected.push($(this).data());
            }
        });

        if (selected.length) {
            this.close(selected).remove();
            return true;
        }
        
        $.error('{{ trans('master.select.min', [1]) }}');
        return false;
    }

</script>
<script>
    $(function(){
        // 文件快捷操作改为选择
        $(document).off('click.file').on('click.file', '.file-item', function(e) {
            var input = $(this).find('input.checkable-control');
                input.prop('checked', !input.prop('checked'));
            $('.checkable').data('checkable').update();
            return false;
        });    
    });
</script>
@endpush
