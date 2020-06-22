@extends('layouts.dialog')

@section('content')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{request('keywords') ?? $content->title ?? trans('content::content.root')}}
        </div>
        <x-search />
        <a href="javascript:location.reload()" class="btn btn-light" title="{{trans('master.refresh')}}">
            <span class="fa fa-sync"></span>
        </a>
    </div>
    @if (! request('keywords'))
    <div class="main-header text-xs bg-light">
        <x-content-admin-breadcrumb :content="$content" />
    </div>
    @endif
    <div class="main-body scrollable">
        <x-content-admin-list :list="$contents" view="grid" :empty="trans('content::content.move.help')"
            :statusbar="false" :checkable="false" :action="false" />
    </div><!-- main-body -->
    @if ($contents->hasPages())
    <div class="main-footer">
        <div class="mx-auto">
            {{ $contents->withQueryString()->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
@push('css')
@endpush
@push('js')
<script type="text/javascript">
    $(function(){
        // 设置当前所在的节点编号
        currentDialog.move_to = {{$content->id ?? 0}};
    })
</script>
@endpush
