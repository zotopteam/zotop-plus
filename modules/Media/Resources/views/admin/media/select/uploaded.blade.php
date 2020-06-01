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
    <div class="main-body scrollable p-2" id="file-upload-dragdrop">
        <div class="card-grid">
            @foreach($files as $file)
            <label class="card-check d-block m-0" data-type="file">
                <input type="{{request('mutiple') ? 'checkbox' : 'radio'}}" name="file" value="{{$file->url}}"
                    class="form-control form-control-check" data-name="{{$file->name}}" data-url="{{$file->url}}"
                    data-type="{{$file->type}}">

                <div class="card card-md bg-light js-contextmenu">
                    <div class="card-thumb pos-r">
                        <div class="pos-a pos-full d-flex justify-content-center bg-image-preview">
                            @if ($file->isImage())
                            <img src="{{$file->url}}" class="img-fluid align-self-center">
                            @else
                            <i class="fa {{$file->icon}} fa-5x fa-fw text-muted align-self-center"></i>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div class="card-text text-sm text-truncate">
                            {{$file->name}}
                        </div>
                        <div class="card-text text-xs text-truncate">
                            <small class="text-success">{{$file->size_human}}</small>
                            @if ($file->isImage())
                            <small>{{$file->width}}px × {{$file->height}}px</small>
                            @endif
                        </div>
                        <div class="contextmenu d-none">
                            @if ($file->isImage())
                            <a href="javascript:;" class="contextmenu-item js-image" data-url="{{$file->url}}"
                                data-title="{{$file->name}}"
                                data-info="{{$file->size_human}} / {{$file->width}}px × {{$file->height}}px">
                                <i class="contextmenu-item-icon fa fa-eye fa-fw"></i>
                                <b class="contextmenu-item-text">{{trans('master.view')}}</b>
                            </a>
                            @endif
                            <a class="contextmenu-item js-prompt" href="javascript:;"
                                data-url="{{route('media.rename',[$file->id])}}"
                                data-prompt="{{trans('core::file.name')}}" data-name="name"
                                data-value="{{$file->name}}">
                                <i class="contextmenu-item-icon fa fa-fw fa-eraser"></i>
                                <b class="contextmenu-item-text">{{trans('master.rename')}}</b>
                            </a>
                            <a class="contextmenu-item js-delete" href="javascript:;"
                                data-url="{{route('media.destroy', $file->id)}}">
                                <i class="contextmenu-item-icon fa fa-times fa-fw"></i>
                                <b class="contextmenu-item-text">{{trans('master.delete')}}</b>
                            </a>
                        </div>
                        <textarea name="data" class="d-none">{!! json_encode($file) !!}</textarea>
                    </div>
                </div>
            </label>
            @endforeach
        </div>
    </div><!-- main-body -->
    @if ($files->lastPage() > 1)
    <div class="main-footer text-sm p-1">
        {{ $files->appends($params)->links() }}
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

        $('[data-type="file"] input').each(function() {
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

</script>
@endpush
