@extends('core::layouts.master')

@section('content')
@include('media::media.side')
<div class="main">
    <div class="main-header">
        @if (empty($keywords))
        @if ($folder_id)
        <div class="main-back">
            <a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>        
        <div class="main-title mr-auto">{{$folder->name}}</div>
        @else
        <div class="main-title mr-auto">{{trans('media::media.root')}}</div>
        @endif
        <div class="main-action">
            <a href="{{route('media.file.upload')}}" class="btn btn-primary">
                <i class="fa fa-fw fa-upload"></i> {{trans('media::file.upload')}}
            </a>
            <a href="javascript:;" class="btn btn-outline-primary js-prompt" data-url="{{route('media.folder.create',[$folder_id])}}"  data-prompt="{{trans('media::folder.name')}}" data-name="name">
                <i class="fa fa-fw fa-folder"></i> {{trans('media::folder.create')}}
            </a>
        </div>
        @else
        <div class="main-back">
            <a href="{{route('media.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>        
        <div class="main-title mx-auto">
            {{trans('core::master.searching', [$keywords])}}
        </div>        
        @endif
        <div class="main-action">
            {form route="media.operate" class="form-inline form-search" method="get"}
                <div class="input-group">
                    {{--{field type="select" name="type" options="Module::data('media::type.options')"}--}}
                    <input name="keywords" value="{{$keywords}}" class="form-control" type="search" placeholder="{{trans('media::media.search.placeholder')}}" required="required" aria-label="Search">
                    <div class="input-group-btn">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i> </button>
                    </div>
                </div>
            {/form}
        </div>        
    </div>
    @if (empty($keywords))    
    <div class="main-header breadcrumb m-0">
        @if ($folder_id)
        <a href="{{route('media.index',[$folder->parent_id])}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-fw fa-arrow-up"></i>{{trans('media::folder.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('media::folder.up')}}</a>
        @endif
        <a class="breadcrumb-item" href="{{route('media.index')}}">{{trans('media::media.root')}}</a>
        @foreach($parents as $p)
        <a class="breadcrumb-item" href="{{route('media.index', $p->id)}}">{{$p->name}}</a> 
        @endforeach      
    </div>
    @endif
    <div class="main-body scrollable">

        {form route="media.operate" class="form-datalist" method="post"}
        <table class="table table-nowrap table-hover table-select">
            <thead>
            <tr>
                <th class="select">
                    <input type="checkbox" class="select-all">
                </th>
                <th colspan="3">{{trans('media::media.name')}}</th>
                <th width="10%">{{trans('media::media.type')}}</th>
                <th width="10%">{{trans('media::media.size')}}</th>
                <th width="10%">{{trans('media::media.created_at')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($folders as $folder)
                <tr class="folder-item" data-url="{{route('media.index', $folder->id)}}">
                    <td class="select">
                        <input type="checkbox" name="folder_id[]" value="{{$folder->id}}" data-type="folder" class="select">
                    </td>
                    <td width="1%" class="text-center pr-2">
                        <i class="fa fa-fw fa-2x fa-folder text-warning"></i>
                    </td>
                    <td class="pl-2">
                        <div class="title text-lg">
                             <a href="{{route('media.index', $folder->id)}}">{{$folder->name}}</a>
                        </div>
                    </td>
                    <td width="10%" class="manage manage-hover text-right">
                            <a class="manage-item js-prompt" href="javascript:;" data-url="{{route('media.folder.edit',[$folder->id])}}"  data-prompt="{{trans('media::folder.name')}}" data-name="name" data-value="{{$folder->name}}">
                                <i class="fa fa-fw fa-eraser"></i> {{trans('core::folder.rename')}}
                            </a>
                            <a href="javascript:;" class="manage-item js-move" data-url="{{route('media.folder.move', $folder->id)}}" data-select="{{route('media.folder.select',[$folder->parent_id])}}" data-title="{{$folder->name}}">
                                <i class="fa fa-arrows-alt fa-fw"></i> {{trans('media::folder.move')}}
                            </a>                               
                            <a class="manage-item js-delete" href="javascript:;" data-url="{{route('media.folder.delete', $folder->id)}}">
                                <i class="fa fa-fw fa-times"></i> {{trans('core::master.delete')}}
                            </a>                        
                    </td>
                    <td>{{trans('media::folder.type')}}</td>
                    <td></td>
                    <td>{{$folder->getCreatedAt()}}</td>
                </tr>
            @endforeach

            @foreach($files as $file)
                <tr>
                    <td class="select">
                        <input type="checkbox" name="file_id[]" value="{{$file->id}}" data-type="file" class="select">
                    </td>                
                    <td width="1%" class="text-center pr-2">
                        @if ($file->isImage())
                            <a href="javascript:;" class="js-image" data-url="{{$file->getUrl()}}" data-title="{{$file->name}}">
                                <div class="icon icon-32"><img src="{{$file->getPreview(32,32)}}"></div>
                            </a>
                        @else
                            <i class="fa {{$file->getIcon()}} fa-2x fa-fw text-warning"></i>
                        @endif                        
                    </td>                
                    <td width="50%" class="pl-2">
                        <div class="title text-md text-wrap">
                            {{$file->name}}
                        </div>
                        <div class="description">
                            @if ($file->isImage())
                            {{$file->width}}px × {{$file->height}}px
                            @endif
                        </div>
                    </td>
                    <td width="10%" class="manage manage-hover text-right">
                        @if ($file->isImage())
                        <a href="javascript:;" class="manage-item js-image" data-url="{{$file->getUrl()}}" data-title="{{$file->name}}">
                            <i class="fa fa-eye fa-fw"></i> {{trans('media::file.view')}}
                        </a>
                        @endif                 
                        <a class="manage-item js-prompt" href="javascript:;" data-url="{{route('media.file.edit',[$file->id])}}"  data-prompt="{{trans('media::file.name')}}" data-name="name" data-value="{{$file->name}}">
                            <i class="fa fa-fw fa-eraser"></i> {{trans('media::file.rename')}}
                        </a>
                        <a href="javascript:;" class="manage-item js-move" data-url="{{route('media.file.move', $file->id)}}" data-select="{{route('media.folder.select',[$file->folder_id])}}" data-title="{{$file->name}}">
                            <i class="fa fa-arrows-alt fa-fw"></i> {{trans('media::file.move')}}
                        </a>                        
                        <a class="manage-item js-delete" href="javascript:;" data-url="{{route('media.file.delete', $file->id)}}">
                            <i class="fa fa-times fa-fw"></i> {{trans('media::file.delete')}}
                        </a>                        
                    </td>
                    <td>{{trans('core::file.type.'.$file->type)}}</td>
                    <td>{{$file->getSize()}}</td>
                    <td>{{$file->getCreatedAt()}}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
        {/form}

    </div><!-- main-body -->
    <div class="main-footer">
        <div class="main-action mr-auto">
            <div class="btn-group dropup">
                <button type="button" class="btn btn-secondary js-select-all">
                    {{trans('media::media.select.all')}}
                </button>
                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-left">
                    <a href="javascript:;" class="dropdown-item js-select-none">{{trans('media::media.select.none')}}</a>
                    <a href="javascript:;" class="dropdown-item js-select" data-type="folder">{{trans('media::media.select.folder')}}</a>
                    <a href="javascript:;" class="dropdown-item js-select" data-type="file">{{trans('media::media.select.file')}}</a>
                </div>
            </div>

            <button type="button" class="btn btn-success js-operate" data-operate="move" data-select="{{route('media.folder.select',[$folder_id])}}">
                <i class="fa fa-arrows-alt fa-fw"></i> {{trans('media::file.move')}}
            </button>
            <button type="button" class="btn btn-danger js-operate" data-operate="delete">
                <i class="fa fa-times fa-fw"></i> {{trans('media::file.delete')}}
            </button>
        </div>    

        {{ $files->links('core::pagination.default') }}
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

    // move dialog
    function movedata(title, select, callback) {
        $.dialog({
            title:title,
            url:select,
            width:500,
            height:400,
            padding:'1rem',
            ok:function() {
                callback(this);
                return false;
            },
            cancel:$.noop,
            oniframeload: function() {
                this.loading(false);
            },
            opener:window
        }, true).loading(true);        
    }

    // post data
    function postdata(url, data, callback) {
        $.post(url, data, function(msg) {
            $.msg(msg);
            // 操作成功
            if (msg.state) callback(); 
            if (msg.url)  location.href = msg.url;
        });
    }

    $(function(){
        // 文件夹双击
        $('.folder-item').on('dblclick',function(){
            location.href = $(this).data('url');
            return false;
        });

        // 文件夹和文件移动
        $('.js-move').on('click',function(){
            var title  = $(this).text() + $(this).data('title');
            var move   = $(this).data('url');
            var select = $(this).data('select');

            movedata(title, select, function(dialog){
                postdata(move, {folder_id:dialog.selected_folder_id}, function(){
                    dialog.close().remove();
                });
            });
        });

        // 选择
        $(function(){
            var selectTable = $('table.table-select').data('selectTable');

            $('.js-select-all').on('click', function() {
                selectTable.selectAll(true);
            });

            $('.js-select-none').on('click', function() {
                selectTable.selectAll(false);
            });

            $('.js-select').on('click', function() {
                selectTable.selectAll(false);
                selectTable.select("[data-type="+ $(this).data('type') +"]",true);
            });          

            $('.js-operate').on('click', function() {   
                if ($(this).hasClass('disabled')) {
                    return false;
                }

                var title   = $(this).text();
                var operate = $(this).data('operate');
                var form    = $('form.form-datalist');
                var action  = form.attr('action');
                var data    = form.serializeArray();
                    data.push({operate:operate});       
                
                if (operate == 'move') {
                    movedata(title, $(this).data('select'), function(dialog) {
                        data.push({folder_id:dialog.selected_folder_id});
                        postdata(action, $.param(data), function(){
                            dialog.close().remove();
                        });
                    })
                } else if(operate == 'delete') {
                    // confirm
                    postdata(action, $.param(data), $.noop);
                } else {
                    postdata(action, $.param(data), $.noop);
                }
            });                 
        });      
    });

</script>
@endpush
