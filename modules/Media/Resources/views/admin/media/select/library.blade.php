@extends('layouts.dialog')

@section('content')
<x-sidebar :data="['core::field.upload.tools', Request::all()]" class="w-auto" />
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{request('keywords') ? trans('master.search.results') : ($media->name ?? trans('media::media.root'))}}
        </div>
        <div class="main-action">
            <x-search />
            @if (!request('keywords'))
            <x-upload-chunk :params="['folder_id'=>$folder_id]" />
            <a href="javascript:;" class="btn btn-primary btn-icon-only js-prompt"
                data-url="{{route('media.create',[$folder_id,'folder'])}}" data-prompt="{{trans('core::folder.name')}}"
                data-name="name" title="{{trans('core::folder.create')}}">
                <i class="fa fa-fw fa-folder-plus"></i> {{trans('core::folder.create')}}
            </a>
            @endif
            <a href="javascript:location.reload();" class="btn btn-light" title="{{trans('master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>

        </div>
    </div>
    @if (!request('keywords'))
    <div class="main-header breadcrumb m-0 p-2 text-xs">
        <x-media-breadcrumb :media="$media" />
    </div>
    @endif
    <div class="main-body scrollable p-2" id="file-upload-dragdrop">
        <x-media-list :list="$media_list" class="grid-sm grid-gap-sm markable" :mutiple="request('mutiple')"
            :moveable="false" />
    </div><!-- main-body -->
    @if ($media_list->lastPage() > 1)
    <div class="main-footer text-sm p-1">
        {{ $media_list->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@push('js')
<script type="text/javascript">
    // 确定按钮回调
    currentDialog.callbacks['ok'] = function () {
        var selected  = new Array();

        $('.file-item').prev('input').each(function() {
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
<script type="text/javascript">
    $(function(){
        // 文件快捷操作改为选择
        $(document).off('click.file').on('click.file', '.file-item', function(e) {
            if ($(e.target).parents('.dropdown').length == 0) {
                var input = $(this).prev('input');
                input.prop('checked', !input.prop('checked'));
            }
            return false;
        });
    });
</script>
@endpush
