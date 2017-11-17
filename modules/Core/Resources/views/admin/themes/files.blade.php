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
            <a href="javascript:;" class="btn btn-primary js-prompt" data-url="{{route('core.file.create',['path'=>path_base($path)])}}" data-prompt="{{trans('core::file.name')}}" data-value="">
                <i class="fa fa-file fa-fw"></i> {{trans('core::file.create')}}
            </a>
        </div>           
    </div>
    <div class="main-header breadcrumb m-0">
        @if($dir == '.')
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('core::folder.up')}}</a>        
        @else
        <a href="{{route('core.themes.files',[$name,'dir'=>dirname($dir)])}}" class="breadcrumb-item breadcrumb-extra"><i class="fa fa-arrow-up fa-fw"></i>{{trans('core::folder.up')}}</a>
        @endif

        @foreach($position as $k=>$v)
        @if($v == '.')
        <a href="{{route('core.themes.files',[$name,'dir'=>$k])}}" class="breadcrumb-item">{{$theme->name}} </a>
        @else
        <a href="{{route('core.themes.files',[$name,'dir'=>$k])}}" class="breadcrumb-item">{{$v}}</a>
        @endif
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
                <tr data-type="folder" data-href="{{route('core.themes.files',[$name,'dir'=>$dir.'/'.basename($folder)])}}">
                    <td width="1%" class="icon icon-sm pr-1">
                        <a href="{{route('core.themes.files',[$name,'dir'=>$dir.'/'.basename($folder)])}}">
                            <i class="fa fa-folder fa-2x fa-fw text-warning"></i>
                        </a>
                    </td>
                    <td class="name pl-2">
                        <a href="{{route('core.themes.files',[$name,'dir'=>$dir.'/'.basename($folder)])}}">{{basename($folder)}}</a>
                    </td>
                    <td>
                    </td>
                    <td width="10%">{{trans('core::folder.type')}}</td>
                    <td></td>                    
                    <td></td>                    
                </tr>
                @endforeach            
                @foreach($files as $file)
                <tr>
                    <td width="1%" class="icon icon-sm pr-1">
                        @if(File::mime($file) == 'image')
                        <div class="image"><img src="{{preview($file)}}"></div>
                        @else
                        <i class="fa fa-file fa-2x fa-fw text-warning"></i>
                        @endif
                    </td>
                    <td class="name pl-2">{{$file->getFileName()}}</td>
                    <td class="manage manage-hover text-right">
                        @switch(File::mime($file))
                            @case('image')
                                <a href="javascript:;" class="manage-item js-open" data-width="80%" data-height="60%">
                                    <i class="fa fa-eye fa-fw text-primary"></i> {{trans('core::file.view')}}
                                </a>
                                @break
                            @case('text')
                                <a href="javascript:;" class="manage-item js-open" data-url="{{route('core.file.editor',['file'=>path_base($file)])}}"  data-width="80%" data-height="60%">
                                    <i class="fa fa-pencil-square fa-fw text-primary"></i> {{trans('core::file.edit')}}
                                </a>
                                @break
                        @endswitch                   
                        <a href="javascript:;" class="manage-item js-post" data-url="{{route('core.file.copy',['file'=>path_base($file)])}}">
                            <i class="fa fa-copy fa-fw text-primary"></i> {{trans('core::file.copy')}}
                        </a>

                        <a href="javascript:;" class="manage-item js-prompt" data-url="{{route('core.file.rename',['file'=>path_base($file)])}}" data-prompt="{{trans('core::file.name')}}" data-value="{{basename($file)}}">
                            <i class="fa fa-eraser fa-fw text-primary"></i> {{trans('core::file.rename')}}
                        </a>

                        <a href="javascript:;" class="manage-item js-delete" data-url="{{route('core.file.delete',['file'=>path_base($file)])}}">
                            <i class="fa fa-trash fa-fw text-primary"></i> {{trans('core::file.delete')}}
                        </a>                                             
                    </td>
                    <td>{{trans('core::file.type.'.File::mime($file))}}</td>
                    <td>{{Format::size(File::size($file))}}</td>             
                    <td>{{Format::date(File::lastModified($file),'Y-m-d')}}</td>                    
                </tr>            
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="main-footer">
        <div class="footer-text mr-auto">{{trans('core::folder.position',[path_base($path)])}} </div>
        <div class="footer-text ml-auto">{{trans('core::folder.count',[count($folders)])}} / {{trans('core::file.count',[count($files)])}}</div>
        
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
