@extends('core::layouts.dialog')

@section('content')
@include('core::file.select_side')
<div class="main">
    <div class="main-header">       
        <div class="main-action mr-auto">
            <div class="breadcrumb px-0">
                @if($upfolder)
                <a href="{{$upfolder['href']}}" class="breadcrumb-item breadcrumb-extra">
                    <i class="fa fa-arrow-up fa-fw"></i> {{trans('core::folder.up')}}
                </a>
                @else
                <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled">
                    <i class="fa fa-arrow-up"></i> {{trans('core::folder.up')}}
                </a>
                @endif

                @foreach($position as $p)
                <a href="{{$p['href']}}" class="breadcrumb-item">
                    @if ($loop->first)
                    <i class="fa fa-home fa-fw"></i>
                    @else
                    <i class="fa fa-folder fa-fw"></i> {{$p['name']}}
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
                <tr data-type="folder" data-href="{{$folder['href']}}">
                    <td width="1%" class="icon icon-sm pr-1">
                        <a href="{{$folder['href']}}">
                            <i class="fa fa-folder fa-2x fa-fw text-warning"></i>
                        </a>
                    </td>
                    <td class="name pl-2">
                        <a href="{{$folder['href']}}">{{$folder['name']}}</a>
                    </td>
                    <td class="manage manage-hover text-right">
                        <a href="javascript:;" class="manage-item js-prompt" data-url="{{route('core.folder.rename',['folder'=>$folder['path']])}}" data-prompt="{{trans('core::folder.name')}}" data-value="{{$folder['name']}}">
                            <i class="fa fa-eraser fa-fw text-primary"></i> {{trans('core::folder.rename')}}
                        </a>

                        <a href="javascript:;" class="manage-item js-delete" data-url="{{route('core.folder.delete',['folder'=>$folder['path']])}}">
                            <i class="fa fa-trash fa-fw text-primary"></i> {{trans('core::folder.delete')}}
                        </a>                        
                    </td>
                    <td width="10%">{{trans('core::folder.type')}}</td>
                    <td></td>
                    <td></td>                    
                </tr>
                @endforeach            
                @foreach($files as $path=>$file)
                <tr>
                    <td width="1%" class="icon icon-sm pr-1">
                        @if($file['type'] == 'image')
                        <div class="icon"><img src="{{$file['icon']}}" width="32"></div>
                        @else
                        <i class="fa {{$file['icon']}} fa-2x fa-fw text-warning"></i>
                        @endif
                    </td>
                    <td class="name pl-2">
                        <div class="title text-md text-wrap">{{$file['name']}}</div>
                        <div class="description">
                            @if($file['type'] == 'image') {{$file['info'][0]}}px × {{$file['info'][1]}}px @endif
                        </div>
                    </td>
                    <td class="manage manage-hover text-right">
                        @switch($file['mime'])
                            @case('image')
                                <a href="javascript:;" class="manage-item js-image" data-url="{{$file['href']}}" data-title="{{$file['name']}}">
                                    <i class="fa fa-eye fa-fw text-primary"></i> {{trans('core::file.view')}}
                                </a>
                                @break
                            @case('text')
                                <a href="javascript:;" class="manage-item js-open" data-url="{{route('core.file.editor',['file'=>$file['path']])}}"  data-width="80%" data-height="60%">
                                    <i class="fa fa-pen-square fa-fw text-primary"></i> {{trans('core::file.edit')}}
                                </a>
                                @break
                        @endswitch
                        <div class="dropdown d-inline-block manage-item">
                            <a href="javascript:;" data-toggle="dropdown">
                                {{trans('core::master.more')}}
                                <i class="fa fa-angle-down" ></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:;" class="dropdown-item js-post" data-url="{{route('core.file.copy',['file'=>$file['path']])}}">
                                    <i class="fa fa-copy fa-fw text-primary"></i> {{trans('core::file.copy')}}
                                </a>

                                <a href="javascript:;" class="dropdown-item js-prompt" data-url="{{route('core.file.rename',['file'=>$file['path']])}}" data-prompt="{{trans('core::file.name')}}" data-value="{{$file['name']}}">
                                    <i class="fa fa-eraser fa-fw text-primary"></i> {{trans('core::file.rename')}}
                                </a>

                                <a href="javascript:;" class="dropdown-item js-delete" data-url="{{route('core.file.delete',['file'=>$file['path']])}}">
                                    <i class="fa fa-times fa-fw text-primary"></i> {{trans('core::file.delete')}}
                                </a>                            
                            </div>
                        </div>                                           
                    </td>
                    <td>{{trans('core::file.type.'.$file['type'])}}</td>
                    <td>{{$file['size']}}</td>
                    <td>{{$file['time']}}</td>                    
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

@endpush
