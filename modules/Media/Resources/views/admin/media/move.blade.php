@extends('layouts.dialog')

@section('content')

<div class="main">
    <div class="main-header">
        <div class="main-title">
            {{trans('media::media.move.to', [$media->name ?? trans('media::media.root')])}}
        </div>
        <div class="main-action ml-auto">
            <x-search />
            @if (!request('keywords'))
            <a href="javascript:;" class="btn btn-primary js-prompt"
                data-url="{{route('media.create',[$folder_id, 'folder'])}}" data-prompt="{{trans('core::folder.name')}}"
                data-name="name">
                <i class="fa fa-fw fa-folder-plus"></i> {{trans('core::folder.create')}}
            </a>
            @endif
            <a href="javascript:location.reload()" class="btn btn-light" title="{{trans('master.refresh')}}">
                <span class="fa fa-sync"></span>
            </a>
        </div>
    </div>
    <div class="main-header bg-light text-xs p-1">
        <x-media-breadcrumb :media="$media" />
    </div>
    <div class="main-body scrollable">
        <x-media-list :list="$media_list" class="grid-sm grid-gap-xs" :checkable="false" :moveable="false" />
    </div><!-- main-body -->
    @if ($media_list->hasPages())
    <div class="main-footer">
        <div class="mx-auto">
            {{ $media_list->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){
    // statusbar
    currentDialog.statusbar('{{trans('media::media.move.help')}}');

    // 设置当前所在的节点编号
    currentDialog.folder_id = {{$media->id ?? 0}};
})
</script>
@endpush
