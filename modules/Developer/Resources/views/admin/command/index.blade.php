@extends('core::layouts.master')

@section('content')
@include('developer::module.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>     
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary js-open" data-url="{{route('developer.command.create',[$name])}}" data-width="800" data-height="300">
                <i class="fa fa-plus"></i> {{trans('core::master.create')}}
            </a>
        </div>
    </div>
    <div class="main-body scrollable">
        
        @if($files)
        <table class="table table-hover">
            <thead>
                <tr>
                    <td colspan="2">{{trans('developer::file.name')}}</td>
                    <td>{{trans('developer::file.path')}}</td>
                    <td>{{trans('developer::file.mtime')}}</td>
                </tr>                
            </thead>
            <tbody>
                
                @foreach($files as $file)
                <tr>
                    <td width="1%" class="icon icon-sm pr-2"><div class="fa fa-file fa-{{$file->getExtension()}} fa-2x text-primary"></div> </td>
                    <td class="pl-2">
                        {{$file->getFilename()}}
                    </td>
                    <td>{{$file->getRealPath()}}</td>
                    <td>{{date('Y-m-d H:i:s',$file->getMTime())}}</td>
                </tr>
                @endforeach
          
            </tbody>
        </table>
        @else
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @endif     
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('developer::command.artisan')}}: php artisan module:make-command TestCommand {{$module}}
        </div>
    </div>    
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('developer::command.path')}}: {{realpath($path)}}
        </div>
    </div>
</div>

@endsection