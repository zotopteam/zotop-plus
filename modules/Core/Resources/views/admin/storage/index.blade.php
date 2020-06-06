@extends('layouts.master')

@section('content')
<x-sidebar data="core::storage.disks" :header="trans('core::storage.title')" :active="$disk" />
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">

            <x-upload-chunk />

            @if ($create = $browser->createFolder())
            <a href="javascript:;" class="btn btn-primary {{$create->class}}" data-url="{{$create->url}}"
                data-name="{{$create->name}}">
                <i class="{{$create->icon}}"></i> {{$create->title}}
            </a>
            @endif
        </div>
    </div>
    <div class="main-header breadcrumb text-xs p-2 m-0">

        @if($upfolder = $browser->upfolder())
        <a href="{{$upfolder->href}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-arrow-up fa-fw"></i> {{trans('core::folder.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled">
            <i class="fa fa-arrow-up fa-fw"></i> {{trans('core::folder.up')}}
        </a>
        @endif

        @foreach ($browser->position() as $p)
        <a href="{{$p->href}}" class="breadcrumb-item">
            @if ($loop->first)
            <i class="fa fa-home fa-fw"></i>
            @else
            <i class="fa fa-folder fa-fw"></i> {{$p->name}}
            @endif
        </a>
        @endforeach

    </div>
    <div class="main-body scrollable">
        <div class="grid grid-sm grid-hover p-3">
            @foreach ($browser->folders() as $folder)
            <div class="grid-item folder-item p-1 cur-p" data-url="{{$folder->href}}">
                <div class="grid-item-icon fh-8">
                    <i class="fa fa-folder fs-6 text-warning"></i>
                </div>
                <div class="grid-item-text d-flex">
                    <div class="grid-item-name text-sm text-break mr-auto">
                        {{$folder->name}}
                    </div>
                    <div class="grid-item-action ml-2 dropdown">
                        <a href="javascript:;" data-toggle="dropdown" class="dropdown-trigger py-1"
                            aria-expanded="false">
                            <i class="fa fa-ellipsis-h fa-fw"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-primary">
                            @foreach ($folder->action as $action)
                            <a href="{{$action.href ?? 'javascript:;'}}" class="dropdown-item {{$action.class ?? ''}}"
                                {!! Html::attributes($action.attrs ?? []) !!}>
                                <i class="dropdown-item-icon {{$action.icon ?? ''}} fa-fw"></i>
                                <b class="dropdown-item-text"> {{$action.text}}</b>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @foreach ($browser->files() as $file)
            <div class="grid-item file-item p-1">

                @if ($file->type == 'image')
                <div class="grid-item-icon fh-8 js-image cur-p" data-url="{{preview($disk.':'.$file->path)}}"
                    data-title="{{$file->name}}" data-info="{{$file->size}} / {{$file->width}}px × {{$file->height}}px">
                    <img src="{{preview($disk.':'.$file->path, 300)}}">
                </div>
                @else
                <div class="grid-item-icon fh-8">
                    <i class="{{$file->icon}} fs-6 text-info"></i>
                </div>
                @endif

                <div class="grid-item-text mt-1 d-flex flex-row justify-content-between">
                    <div class="grid-item-name text-sm text-break mr-auto">
                        {{$file->name}}
                    </div>
                    <div class="grid-item-action ml-2 dropdown">
                        <a href="javascript:;" data-toggle="dropdown" class="dropdown-trigger py-1"
                            aria-expanded="false">
                            <i class="fa fa-ellipsis-h fa-fw"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-primary">
                            @foreach ($file->action as $action)
                            <a href="{{$action.href ?? 'javascript:;'}}" class="dropdown-item {{$action.class ?? ''}}"
                                {!! Html::attributes($action.attrs ?? []) !!}>
                                <i class="dropdown-item-icon {{$action.icon ?? ''}} fa-fw"></i>
                                <b class="dropdown-item-text"> {{$action.text}}</b>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div> <!-- main-body -->
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){
        $(document).on('click.folder', '.folder-item', function(e) {
            if ($(e.target).parents('.dropdown').length == 0) {
                location.href = $(this).data('url');
            }
        });
    })
</script>
@endpush
