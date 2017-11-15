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

        </div>           
    </div>
    <div class="main-header breadcrumb m-0">
        @if($dir == '.')
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('core::themes.file.up')}}</a>        
        @else
        <a href="{{route('core.themes.files',[$name,'dir'=>dirname($dir)])}}" class="breadcrumb-item breadcrumb-extra"><i class="fa fa-arrow-up fa-fw"></i>{{trans('core::themes.file.up')}}</a>
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
                    <td colspan="2">{{trans('core::themes.file.name')}}</td>
                    <td width="15%">{{trans('core::themes.file.type')}}</td>
                    <td width="15%">{{trans('core::themes.file.size')}}</td>
                    <td width="15%">{{trans('core::themes.file.mtime')}}</td>
                </tr>
            </thead>        
            <tbody>
                @foreach($folders as $folder)
                <tr data-type="folder" data-href="{{route('core.themes.files',[$name,'dir'=>$dir.'/'.basename($folder)])}}">
                    <td width="1%" class="icon icon-sm pr-2">
                        <a href="{{route('core.themes.files',[$name,'dir'=>$dir.'/'.basename($folder)])}}"><i class="fa fa-folder fa-2x fa-fw text-primary"></i></a>
                    </td>
                    <td class="name pl-2">
                        <a href="{{route('core.themes.files',[$name,'dir'=>$dir.'/'.basename($folder)])}}">{{basename($folder)}}</a>
                    </td>
                    <td width="10%">{{trans('core::themes.file.type.folder')}}</td>
                    <td></td>                    
                    <td></td>                    
                </tr>
                @endforeach            
                @foreach($files as $file)
                <tr>
                    <td width="1%" class="icon icon-sm pr-2"><i class="fa fa-file fa-2x fa-fw text-warning"></i></td>
                    <td class="name pl-2">{{$file->getFileName()}}</td>
                    <td width="10%">{{trans('core::themes.file.type.file')}}</td>
                    <td width="10%">{{round($file->getSize()/1024,2)}} KB</td>                    
                    <td width="10%">{{date('Y-m-d H:i:s',$file->getMTime())}}</td>                    
                </tr>            
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="main-footer">
        <div class="footer-text mr-auto">{{trans('core::themes.path',[$path])}} </div>
        <div class="footer-text ml-auto">{{trans('core::themes.folders.count',[count($folders)])}} / {{trans('core::themes.files.count',[count($files)])}}</div>
        
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
