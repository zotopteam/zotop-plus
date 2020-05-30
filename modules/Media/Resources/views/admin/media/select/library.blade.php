@extends('layouts.dialog')

@section('content')
@include('media::media.select.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">

            <x-upload-chunk :params="['folder_id'=>$parent_id]" />

            <a href="javascript:;" class="btn btn-primary btn-icon-only js-prompt"
                data-url="{{route('media.create',[$parent_id,'folder'])}}" data-prompt="{{trans('core::folder.name')}}"
                data-name="name" title="{{trans('core::folder.create')}}">
                <i class="fa fa-fw fa-folder-plus"></i> {{trans('core::folder.create')}}
            </a>
            <a href="javascript:location.reload();" class="btn btn-light" title="{{trans('master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>
        </div>
    </div>
    <div class="main-header breadcrumb m-0 p-2 text-xs">
        @if ($parent_id)
        <a href="{{$parent_url}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-fw fa-arrow-up"></i>{{trans('media::media.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i
                class="fa fa-arrow-up"></i>{{trans('media::media.up')}}</a>
        @endif
        <a class="breadcrumb-item" href="{{$root_url}}">{{trans('media::media.root')}}</a>
        @foreach($parents as $p)
        <a class="breadcrumb-item" href="{{$p->url}}">{{$p->name}}</a>
        @endforeach
    </div>
    <div class="main-body scrollable p-2" id="file-upload-dragdrop">

        <div class="card-grid">
            @foreach($media as $m)
            <label class="card-check d-flex flex-column" data-select="{{$m->isFolder() ? 'no' : 'yes'}}"
                data-type="{{$m->type}}" data-link="{{$m->link}}">
                @if (request()->input('select', 0) == 1)
                <input type="radio" name="file_ids[]" value="{{$m->id}}" class="form-control form-control-check">
                @else
                <input type="checkbox" name="file_ids[]" value="{{$m->id}}" class="form-control form-control-check">
                @endif
                <div class="card bg-light js-contextmenu">
                    <div class="card-thumb pos-r">
                        @if ($m->isFolder())
                        <div class="d-flex justify-content-center bg-white pos-a pos-full">
                            <i class="fa fa-folder fa-6x fa-fw text-warning align-self-center"></i>
                        </div>
                        @elseif ($m->isImage())
                        <div class="d-flex justify-content-center bg-image-preview pos-a pos-full">
                            <img src="{{$m->link}}" class="align-self-center">
                        </div>
                        @else
                        <div class="d-flex justify-content-center bg-white pos-a pos-full">
                            <i class="{{$m->icon}} fa-5x fa-fw text-muted align-self-center"></i>
                        </div>
                        @endif
                    </div>

                    <div class="card-body p-2">
                        <div class="card-text text-sm text-truncate">
                            {{$m->name}}
                        </div>
                        <div class="card-text text-xs text-truncate">
                            @if($m->isFolder())
                            <small class="text-success">{{trans('core::folder.type')}}</small>
                            @else
                            <small class="text-success">{{trans('core::file.type.'.$m->type)}}</small>
                            @endif
                            <small class="text-info">{{$m->size_human}}</small>
                            @if ($m->isImage())
                            <small>{{$m->width}}px × {{$m->height}}px</small>
                            @endif
                        </div>
                        <div class="contextmenu d-none">
                            @if ($m->isImage())
                            <a href="javascript:;" class="contextmenu-item js-image" data-url="{{$m->link}}"
                                data-title="{{$m->name}}"
                                data-info="{{$m->size_human}} / {{$m->width}}px × {{$m->height}}px">
                                <i class="contextmenu-item-icon fa fa-eye fa-fw"></i>
                                <b class="contextmenu-item-text">{{trans('master.view')}}</b>
                            </a>
                            @endif

                            @if($m->isFolder())
                            <a class="contextmenu-item js-prompt" href="javascript:;"
                                data-url="{{route('media.rename',[$m->id])}}"
                                data-prompt="{{trans('core::folder.name')}}" data-name="name" data-value="{{$m->name}}">
                                <i class="contextmenu-item-icon fa fa-fw fa-eraser"></i>
                                <b class="contextmenu-item-text">{{trans('master.rename')}}</b>
                            </a>
                            @else
                            <a class="contextmenu-item js-prompt" href="javascript:;"
                                data-url="{{route('media.rename',[$m->id])}}" data-prompt="{{trans('core::file.name')}}"
                                data-name="name" data-value="{{$m->name}}">
                                <i class="contextmenu-item-icon fa fa-fw fa-eraser"></i>
                                <b class="contextmenu-item-text">{{trans('master.rename')}}</b>
                            </a>
                            @endif

                            <a class="contextmenu-item js-delete" href="javascript:;"
                                data-url="{{route('media.destroy', $m->id)}}">
                                <i class="contextmenu-item-icon fa fa-times fa-fw"></i>
                                <b class="contextmenu-item-text">{{trans('master.delete')}}</b>
                            </a>
                        </div>
                        <textarea name="data" class="d-none">{!! json_encode($m) !!}</textarea>
                    </div>
                </div>
            </label>
            @endforeach
        </div>


    </div><!-- main-body -->
    @if ($media->lastPage() > 1)
    <div class="main-footer text-sm p-1">
        {{ $media->appends($params)->links() }}
    </div>
    @endif
</div>
@endsection

@push('css')
<style type="text/css">
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(10rem, 1fr));
        grid-row-gap: .5rem;
        grid-column-gap: .5rem;
        padding: .5rem;
    }

    .card-thumb {
        padding-bottom: 60%;
        overflow: hidden;
    }

    .card-thumb img {
        max-width: 100%;
        max-height: 100%;
    }
</style>
@endpush
@push('js')

<script type="text/javascript">
    // 确定按钮回调
    currentDialog.callbacks['ok'] = function () {
        var selected  = new Array();

        $('[data-select="yes"]').each(function() {
            if ($(this).find('input.form-control-check').is(':checked')) {
                var data = $(this).find('[name=data]').val();
                    data = $.parseJSON(data);
                selected.push(data);                
            }
        });

        if (selected.length) {
            this.close(selected).remove();
        } else {
            $.error('{{ trans('master.select.min', [1]) }}');
        }
        
        return false;
    }

</script>
<script type="text/javascript">
    $(function(){
        // 文件夹单击
        $('[data-type="folder"]').on('click',function(){
            location.href = $(this).data('link');
            return false;
        });

        // 文件单击
        $('[data-type="file"]').on('click', function(event) {
            // code
        });         
    });

</script>
@endpush
