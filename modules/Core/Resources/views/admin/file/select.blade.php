@extends('core::layouts.dialog')

@section('content')
<div class="main">
    <div class="main-header">       
        <div class="main-action mr-auto">
            <div class="breadcrumb px-0">
                @if($upfolder)
                <a href="{{$upfolder->href}}" class="breadcrumb-item breadcrumb-extra">
                    <i class="fa fa-arrow-up fa-fw"></i> {{trans('core::folder.up')}}
                </a>
                @else
                <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled">
                    <i class="fa fa-arrow-up"></i> {{trans('core::folder.up')}}
                </a>
                @endif

                @foreach($position as $p)
                <a href="{{$p->href}}" class="breadcrumb-item">
                    @if ($loop->first)
                    <i class="fa fa-home fa-fw"></i>
                    @else
                    <i class="fa fa-folder fa-fw"></i> {{$p->name}}
                @endif
                </a>
                @endforeach
            </div>            
        </div>
        <div class="main-action">
            <div class="btn-group">
                <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-plus"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a href="javascript:;" class="dropdown-item js-prompt" data-url="{{route('core.file.create',['path'=>$path])}}" data-prompt="{{trans('core::file.name')}}" data-value="">
                        <i class="dropdown-item-icon fa fa-file fa-fw"></i>
                        <b class="dropdown-item-text">{{trans('core::file.create')}}</b>
                    </a>
                    <a href="javascript:;" class="dropdown-item js-prompt" data-url="{{route('core.folder.create',['path'=>$path])}}" data-prompt="{{trans('core::folder.name')}}" data-value="">
                        <i class="dropdown-item-icon fa fa-folder fa-fw"></i>
                        <b class="dropdown-item-text">{{trans('core::folder.create')}}</b>
                    </a>                    
                </div>
            </div>

            <a href="javascript:location.reload();" class="btn btn-light" title="{{trans('core::master.refresh')}}">
                <i class="fa fa-sync"></i>
            </a>                
        </div>           
    </div>
    <div class="main-body scrollable">
        <table class="table table-hover table-nowrap">
            <thead>
                <tr>
                    <td colspan="2">{{trans('core::file.name')}}</td>
                    <td width="12%"></td>
                    <td width="12%">{{trans('core::file.type')}}</td>
                    <td width="12%">{{trans('core::file.size')}}</td>
                    <td width="12%">{{trans('core::file.mtime')}}</td>
                </tr>
            </thead>        
            <tbody>
                @foreach($folders as $folder)
                <tr data-type="folder" data-href="{{$folder->href}}">
                    <td width="1%" class="icon icon-sm pr-1">
                        <a href="{{$folder->href}}">
                            <i class="fa fa-folder fa-2x fa-fw text-warning"></i>
                        </a>
                    </td>
                    <td class="name pl-2">
                        <a href="{{$folder->href}}">{{$folder->name}}</a>
                    </td>
                    <td class="manage manage-hover text-right">

                        <a href="javascript:;" class="manage-item js-prompt" data-url="{{route('core.folder.rename',['folder'=>$folder->path])}}" data-prompt="{{trans('core::folder.name')}}" data-value="{{$folder->name}}">
                            <i class="fa fa-eraser fa-fw text-primary"></i> {{trans('core::folder.rename')}}
                        </a>

                        <a href="javascript:;" class="manage-item js-delete" data-url="{{route('core.folder.delete',['folder'=>$folder->path])}}">
                            <i class="fa fa-trash fa-fw text-primary"></i> {{trans('core::folder.delete')}}
                        </a>                        
                    </td>
                    <td width="10%">{{$folder->typename}}</td>
                    <td>{{$folder->size}}</td>
                    <td>{{$folder->time}}</td>                    
                </tr>
                @endforeach            
                @foreach($files as $path=>$file)
                <tr data-type="file">
                    <td width="1%" class="icon icon-sm pr-1">
                        @if($file->type == 'image')
                        <div class="icon"><img src="{{preview($file->realpath,32,32)}}" width="32"></div>
                        @else
                        <i class="fa {{$file->icon}} fa-2x fa-fw text-warning"></i>
                        @endif
                    </td>
                    <td class="name pl-2">
                        <div class="title text-md text-wrap">{{$file->name}}</div>
                        <div class="description">
                            @if($file->type == 'image') {{$file->width}}px × {{$file->height}}px @endif
                        </div>
                        <textarea name="data" class="d-none">{!! json_encode($file) !!}</textarea>
                    </td>
                    <td class="manage manage-hover text-right">
                        @switch($file->mime)
                            @case('image')
                                <a href="javascript:;" class="manage-item js-image" data-url="{{$file->url ?: preview($file->realpath)}}" data-title="{{$file->name}}">
                                    <i class="fa fa-eye fa-fw text-primary"></i> {{trans('core::file.view')}}
                                </a>
                                @break
                            @case('text')
                                <a href="javascript:;" class="manage-item js-open" data-url="{{route('core.file.editor',['file'=>$file->path])}}"  data-width="80%" data-height="60%">
                                    <i class="fa fa-edit fa-fw text-primary"></i> {{trans('core::file.edit')}}
                                </a>
                                @break
                        @endswitch
                        <div class="dropdown d-inline-block manage-item">
                            <a href="javascript:;" data-toggle="dropdown">
                                {{trans('core::master.more')}}
                                <i class="fa fa-angle-down" ></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:;" class="dropdown-item js-post" data-url="{{route('core.file.copy',['file'=>$file->path])}}">
                                    <i class="fa fa-copy fa-fw text-primary"></i> {{trans('core::file.copy')}}
                                </a>

                                <a href="javascript:;" class="dropdown-item js-prompt" data-url="{{route('core.file.rename',['file'=>$file->path])}}" data-prompt="{{trans('core::file.name')}}" data-value="{{$file->name}}">
                                    <i class="fa fa-eraser fa-fw text-primary"></i> {{trans('core::file.rename')}}
                                </a>

                                <a href="javascript:;" class="dropdown-item js-delete" data-url="{{route('core.file.delete',['file'=>$file->path])}}">
                                    <i class="fa fa-times fa-fw text-primary"></i> {{trans('core::file.delete')}}
                                </a>                            
                            </div>
                        </div>                                           
                    </td>
                    <td>{{$file->typename}}</td>
                    <td>{{$file->size}}</td>
                    <td>{{$file->time}}</td>                    
                </tr>            
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')
<script type="text/javascript">
    var select = {{$select}};

    // 回调
    function callback()
    {
        var selected  = new Array();

        $('[data-type="file"]').filter('.selected').each(function() {
            var data = $(this).find('[name=data]').val();
                data = $.parseJSON(data);
            selected.push(data);
        });

        $dialog.selected = selected;   
    }

    // 确定按钮回调
    $dialog.callbacks['ok'] = function () {
        var selected  = new Array();

        $('[data-type="file"]').filter('.selected').each(function() {
            var data = $(this).find('[name=data]').val();
                data = $.parseJSON(data);
            selected.push(data);
        });

        if (selected.length) {
            this.close(selected).remove(); 
        }
        
        return false;
    }    

    $(function(){
        // 文件夹双击
        $('[data-type="folder"]').on('dblclick',function(){
            location.href = $(this).data('href');
            return false;
        });

        // 文件双击，直接返回
        $('[data-type="file"]').on('dblclick', function(){
            $(this).addClass('selected').siblings(".selected").removeClass('selected');  //单选
            $dialog.ok();
            return false;
        });

        // 文件单击
        $('[data-type="file"]').on('click', function(event) {
            //event.preventDefault();
            //当点击为按钮时，禁止选择
            if($(event.target).prop('tagName') == 'A') return ;

            // 选择和取消选择
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass("selected");
            } else if ( select == 1 ) {
                $(this).addClass('selected').siblings(".selected").removeClass('selected'); //单选
            } else {
                var num = $('.selected').length;
                if( select>1 && num > select ) {
                    $.error(select);
                    return false;
                }else{
                    $(this).addClass("selected");
                }
            }

            event.stopPropagation();
            return false;
        });  
    });    
</script>
@endpush
