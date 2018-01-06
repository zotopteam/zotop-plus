@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('core.themes.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>          
        <div class="main-title mx-auto">
            {{$theme->title}} {{$title}}
        </div>
        <div class="main-action">            
            <a href="javascript:;" class="btn btn-primary js-prompt" data-url="{{route('core.file.create',['path'=>$path])}}" data-prompt="{{trans('core::file.name')}}" data-value="">
                <i class="fa fa-file fa-fw"></i> {{trans('core::file.create')}}
            </a>
            <a href="javascript:;" class="btn btn-primary js-prompt" data-url="{{route('core.folder.create',['path'=>$path])}}" data-prompt="{{trans('core::folder.name')}}" data-value="">
                <i class="fa fa-folder fa-fw"></i> {{trans('core::folder.create')}}
            </a>            
        </div>           
    </div>
    <div class="main-header breadcrumb m-0">

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
                @foreach($files as $file)
                <tr data-type="file">
                    <td width="1%" class="icon icon-sm pr-1">
                        @if($file->type == 'image')
                        <div class="icon bg-image-preview"><img src="{{preview($file->realpath,32,32)}}" width="32"></div>
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
    <div class="main-footer">
        <div class="footer-text mr-auto">{{trans('core::folder.position',[$path])}}</div>
        <div class="footer-text ml-auto">{{trans('core::folder.count',[count($folders)])}} / {{trans('core::file.count',[count($files)])}}</div>
        
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')
<script type="text/javascript">
    $(function(){
        // 文件夹双击
        $('[data-type="folder"]').on('dblclick',function(){
            location.href = $(this).data('href');
            return false;
        });
    });    
</script>
@endpush
