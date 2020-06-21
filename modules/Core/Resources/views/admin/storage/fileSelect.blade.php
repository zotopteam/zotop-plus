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
        <div class="grid grid-sm grid-hover checkable selectable p-3">
            @foreach ($browser->folders() as $folder)
            <div class="grid-item folder-item cur-p h-100 p-1" data-type="folder" data-url="{{$folder->href}}">
                <div class="grid-item-icon fh-8">
                    <i class="fa fa-folder fs-6 text-warning"></i>
                </div>
                <div class="grid-item-text d-flex flex-row px-1">
                    <div class="grid-item-name text-sm text-break mr-auto">
                        {{$folder->name}}
                    </div>
                    <div class="grid-item-action js-click-ignore ml-2 dropdown">
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
            @foreach ($browser->files() as $i => $file)

            <div class="grid-item checkable-item selectable-item file-item cur-p p-1">
                <div class="grid-item-badge">
                    <input type="{{request('mutiple') ? 'checkbox' : 'radio'}}"
                        name="{{request('mutiple') ? 'file[]' : 'file'}}" value="{{$file->url}}"
                        id="checkable-control-{{$i}}" class="checkable-control d-none" data-name="{{$file->name}}"
                        data-url="{{$file->url}}" data-type="{{$file->type}}">
                    <label for="checkable-control-{{$i}}" class="checkable-control-circle hover-show"></label>
                </div>
                <div class="grid-item-icon fh-8">
                    @if ($file->type == 'image')
                    <img src="{{preview($disk.':'.$file->path, 300)}}">
                    @else
                    <i class="{{$file->icon}} fs-6 text-info"></i>
                    @endif
                </div>
                <div class="grid-item-text d-flex flex-row justify-content-between p-1">
                    <div class="grid-item-name text-sm text-break mr-auto">
                        {{$file->name}}
                    </div>
                    <div class="grid-item-action js-click-ignore ml-2 dropdown">
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
    // 确定按钮回调
    currentDialog.callbacks['ok'] = function () {
        var selected = new Array();

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
<script type="text/javascript">
    $(function(){
        // 文件夹点击
        $(document).on('click.folder', '.folder-item', function(e) {
            if ($(e.target).parents('.js-click-ignore').length == 0) {
                location.href = $(this).data('url');
                return true;
            }
        });      
    });
</script>
<script type="text/javascript">
    $(function(){
        // 文件快捷操作改为选择
        $(document).off('click.file').on('click.file', '.file-item', function(e) {
            if ($(e.target).parents('.js-click-ignore').length == 0) {
                var input = $(this).find('input.checkable-control');
                input.prop('checked', !input.prop('checked'));
                $('.checkable').data('checkable').update();
            }
            return false;
        });
    });
</script>

@if(request('mutiple'))
<script>
    $(function(){
        // 拖动选择
        $('.selectable').selectable({
            filter: '.selectable-item',
            distance: 30,
            selecting:function(evnet, ui){
                $(ui.selecting).find('.checkable-control').prop('checked', true);
                $('.checkable').data('checkable').update();
            },
            unselecting:function(evnet, ui){
                $(ui.unselecting).find('.checkable-control').prop('checked', false);
                $('.checkable').data('checkable').update();
            }
        });
    });
</script>
@endif
@endpush
