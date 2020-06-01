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

            @if ($create = $browser->createFolder())
            <a href="javascript:;" class="btn btn-primary {{$create->class}}" data-url="{{$create->url}}"
                data-name="{{$create->name}}">
                <i class="{{$create->icon}}"></i> {{$create->title}}
            </a>
            @endif

            <a href="javascript:location.reload()" class="btn btn-light" title="{{trans('master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>
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
        <div class="grid grid-sm p-3">
            @foreach ($browser->folders() as $folder)
            <label class="grid-item grid-item-folder card-check disabled cur-p h-100" data-type="folder"
                data-url="{{$folder->href}}">
                <div class="card">
                    <div class="grid-item-icon d-flex justify-content-center">
                        <i class="fa fa-folder text-warning align-self-center"></i>
                    </div>
                    <div class="grid-item-text d-flex flex-row px-1">
                        <div class="grid-item-name mr-auto">
                            {{$folder->name}}
                        </div>
                        <div class="grid-item-action ml-2 dropdown">
                            <a href="javascript:;" data-toggle="dropdown" class="dropdown-trigger"
                                aria-expanded="false">
                                <i class="fa fa-ellipsis-h fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-primary">
                                @foreach ($folder->action as $action)
                                <a href="{{$action.href ?? 'javascript:;'}}"
                                    class="dropdown-item {{$action.class ?? ''}}" {!! Html::attributes($action.attrs ??
                                    []) !!}>
                                    <i class="dropdown-item-icon {{$action.icon ?? ''}} fa-fw"></i>
                                    <b class="dropdown-item-text"> {{$action.text}}</b>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </label>
            @endforeach
            @foreach ($browser->files() as $file)
            <label class="grid-item grid-item-file cur-p card-check" data-select="yes" data-type="file">
                <input type="{{request('mutiple') ? 'checkbox' : 'radio'}}" name="file" value="{{$file->url}}"
                    class="form-control form-control-check" data-name="{{$file->name}}" data-url="{{$file->url}}"
                    data-type="{{$file->type}}">
                <div class="card">
                    @if ($file->type == 'image')
                    <div class="grid-item-icon d-flex justify-content-center">
                        <img src="{{preview($disk.':'.$file->path, 300)}}" class="img-fluid align-self-center">
                    </div>
                    @else
                    <div class="grid-item-icon d-flex justify-content-center">
                        <i class="{{$file->icon}} text-info align-self-center"></i>
                    </div>
                    @endif

                    <div class="grid-item-text d-flex flex-row justify-content-between px-1">
                        <div class="grid-item-name mr-auto">
                            {{$file->name}}
                        </div>
                        <div class="grid-item-action ml-2 dropdown">
                            <a href="javascript:;" data-toggle="dropdown" class="dropdown-trigger"
                                aria-expanded="false">
                                <i class="fa fa-ellipsis-h fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-primary">
                                @foreach ($file->action as $action)
                                <a href="{{$action.href ?? 'javascript:;'}}"
                                    class="dropdown-item {{$action.class ?? ''}}" {!! Html::attributes($action.attrs ??
                                    []) !!}>
                                    <i class="dropdown-item-icon {{$action.icon ?? ''}} fa-fw"></i>
                                    <b class="dropdown-item-text"> {{$action.text}}</b>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </label>
            @endforeach
        </div>
    </div> <!-- main-body -->
</div>
@endsection

@push('css')
<style type="text/css">
    .grid-storage {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(10rem, 1fr));
        grid-row-gap: 1rem;
        grid-column-gap: 1rem;
        padding: 0rem;
    }

    .grid-item .grid-item-icon {
        width: 100%;
        height: 8rem;
        font-size: 6rem;
        background: #f7f7f7;
        border-radius: 3px;
        overflow: hidden;
    }

    .grid-item .grid-item-text {
        padding: .5rem 0;
    }

    .grid-item .grid-item-name {
        font-size: 0.875rem;
        word-break: break-all;
        text-align: left;
    }
</style>
@endpush
@push('js')
<script type="text/javascript">
    // 确定按钮回调
    currentDialog.callbacks['ok'] = function () {
        var selected = new Array();

        $('[data-type="file"] input').each(function () {
            if ($(this).is(':checked')) {
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

    $(function(){
        // 文件夹点击
        $('[data-type="folder"]').on('click', function(e) {
            if ($(e.target).parents('.grid-item-action').length == 0) {
                location.href = $(this).data('url');
            }
        });
        
    });
</script>
@endpush
