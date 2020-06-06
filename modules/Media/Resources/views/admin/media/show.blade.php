@extends('layouts.dialog')

@section('content')
<div class="main w-100 justify-content-center align-items-center p-3">
    @if ($media->type == 'folder')
    <i class="fa fa-folder fs-16 text-warning align-self-center"></i>
    @elseif ($media->type == 'image')
    <img src="{{preview($media->diskpath)}}" class="img-fluid align-self-center">
    @else
    <i class="{{$media->icon}} fs-16 text-info align-self-center"></i>
    @endif
</div>
<div class="side scrollable fw-35">

    <table class="table table-md">
        <tbody>
            <tr>
                <td class="text-nowrap" width="20%">{{trans('media::media.name')}}</td>
                <td>
                    <div class="text-break">
                        <b>{{$media->name}}</b>
                        <a href="javascript:;" class="text-primary js-prompt" title="{{trans('master.rename')}}"
                            data-url="{{route('media.rename', $media->id)}}" data-name="name"
                            data-value="{{$media->name}}">
                            <i class="fa fa-eraser"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="text-nowrap" width="20%">{{trans('media::media.type')}}</td>
                <td>
                    <div class="text-break"> {{$media->type == 'folder' ? trans('core::folder.type') :
                        trans("core::file.type.{$media->type}")}} </div>
                </td>
            </tr>
            @if ($media->disk)
            <tr>
                <td class="text-nowrap" width="20%">{{trans('media::media.disk')}}</td>
                <td>
                    <div class="text-break"> {{Arr::get($disks, "{$media->disk}.text")}} </div>
                </td>
            </tr>
            @endif
            @if ($media->size)
            <tr>
                <td class="text-nowrap" width="20%">{{trans('media::media.size')}}</td>
                <td>
                    <div class="text-break"> {{size_format($media->size)}} </div>
                </td>
            </tr>
            @endif
            @if ($media->width && $media->height)
            <tr>
                <td class="text-nowrap" width="20%">{{trans('media::media.width_height')}}</td>
                <td>
                    <div class="text-break"> {{$media->width}}px × {{$media->height}}px </div>
                </td>
            </tr>
            @endif
            @if ($media->type == 'folder')
            <tr>
                <td class="text-nowrap" width="20%">{{trans('media::media.children.count')}}</td>
                <td>
                    <div class="text-break"> {{$media->children->count()}} </div>
                </td>
            </tr>
            @endif
            <tr>
                <td class="text-nowrap" width="20%">{{trans('media::media.path')}}</td>
                <td>
                    <a href="javascript:;" class="btn btn-outline-primary btn-sm text-left js-move"
                        title="{{trans('master.move')}}" data-id="{{$media->id}}" data-url="{{route('media.move')}}"
                        data-title="{{trans('master.move')}}">
                        {{trans('media::media.root')}}
                        @foreach ($media->parents as $p)
                        @if (!$loop->last)
                        / {{$p->name}}
                        @endif
                        @endforeach
                    </a>
                </td>
            </tr>
            @if ($media->hash)
            <tr>
                <td class="text-nowrap" width="20%">{{trans('media::media.hash')}}</td>
                <td>
                    <div class="text-break"> {{$media->hash}} </div>
                </td>
            </tr>
            @endif
            <tr>
                <td class="text-nowrap" width="20%">{{trans('media::media.username')}}</td>
                <td>
                    <div class="text-break"> {{$media->user->username}} </div>
                </td>
            </tr>
            <tr>
                <td class="text-nowrap" width="20%">{{trans('master.created_at')}}</td>
                <td>
                    <div class="text-break"> {{$media->created_at}} </div>
                </td>
            </tr>
            <tr>
                <td class="text-nowrap" width="20%">{{trans('master.updated_at')}}</td>
                <td>
                    <div class="text-break"> {{$media->updated_at}} </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@push('js')
<script>
    $(function(){        
        // 单个文件夹和文件移动
        $(document).on('click', 'a.js-move',function(event){            
            var operator = $(this);
            var dialog = $.dialog({
                title : operator.data('title'),
                url : operator.data('url'),
                width : '85%',
                height : '70%',
                ok : function() {
                    $.post(operator.data('url'), {id: operator.data('id'), folder_id: dialog.folder_id}, function (msg) {
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
        });
    });
</script>
@endpush
