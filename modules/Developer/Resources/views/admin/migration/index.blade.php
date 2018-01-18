@extends('core::layouts.master')

@section('content')
@include('developer::module.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>     
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary js-open" data-url="{{route('developer.migration.create',[$name])}}" data-width="800" data-height="300">
                <i class="fa fa-plus fa-fw"></i> {{trans('developer::migration.create')}}
            </a>
            <div class="btn-group">
                <a href="javascript:;" class="btn btn-danger js-confirm" data-url="{{route('developer.migration.execute',[$name,'migrate'])}}" data-confirm="{{trans('developer::migration.migrate.tips')}}">
                    <i class="fa fa-share fa-fw"></i> {{trans('developer::migration.migrate')}}
                </a>
                <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="javascript:;" class="dropdown-item js-confirm" data-url="{{route('developer.migration.execute',[$name,'rollback'])}}" data-confirm="{{trans('developer::migration.rollback.tips')}}">
                        <i class="dropdown-item-icon fa fa-reply fa-fw"></i>
                        <b class="dropdown-item-text">{{trans('developer::migration.rollback')}}</b>
                    </a>
                    <a href="javascript:;" class="dropdown-item js-confirm" data-url="{{route('developer.migration.execute',[$name,'reset'])}}" data-confirm="{{trans('developer::migration.reset.tips')}}">
                        <i class="dropdown-item-icon fa fa-reply-all fa-fw"></i>
                        <b class="dropdown-item-text">{{trans('developer::migration.reset')}}</b>
                    </a>
                    <a href="javascript:;" class="dropdown-item js-confirm" data-url="{{route('developer.migration.execute',[$name,'refresh'])}}" data-confirm="{{trans('developer::migration.refresh.tips')}}">
                        <i class="dropdown-item-icon fa fa-sync fa-fw"></i>
                        <b class="dropdown-item-text">{{trans('developer::migration.refresh')}}</b>
                    </a>                                       
                </div>                                               
            </div>       
        </div>
    </div>
    <div class="main-body scrollable">
        
        @if($files)
        <table class="table table-hover">
            <thead>
                <tr>
                    <td colspan="3">{{trans('developer::file.name')}}</td>
                    <td>{{trans('developer::file.mtime')}}</td>
                </tr>                
            </thead>
            <tbody>
                
                @foreach($files as $file)
                <tr>
                    <td width="1%" class="icon icon-sm pr-2"><div class="fa fa-file fa-{{$file->getExtension()}} fa-2x text-primary"></div> </td>
                    <td class="pl-2">
                        <div class="title">{{$file->getFilename()}}</div>
                        <div class="description">
                            {{$file->getRealPath()}}
                        </div>
                    </td>
                    <td class="manage manage-hover text-right">
                        <a href="javascript:;" class="manage-item js-open" data-url="{{route('core.file.editor',['file'=>path_base($file)])}}"  data-width="80%" data-height="80%">
                            <i class="fa fa-pen-square fa-fw text-primary"></i> {{trans('core::file.edit')}}
                        </a>
                        <div class="dropdown d-inline-block manage-item">
                            <a href="javascript:;" data-toggle="dropdown">
                                {{trans('core::master.more')}}
                                <i class="fa fa-angle-down" ></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:;" class="dropdown-item js-post" data-url="{{route('core.file.copy',['file'=>path_base($file)])}}">
                                    <i class="fa fa-copy fa-fw text-primary"></i> {{trans('core::file.copy')}}
                                </a>
                                <a href="javascript:;" class="dropdown-item js-prompt" data-url="{{route('core.file.rename',['file'=>path_base($file)])}}" data-prompt="{{trans('core::file.name')}}" data-value="{{basename($file)}}">
                                    <i class="fa fa-eraser fa-fw text-primary"></i> {{trans('core::file.rename')}}
                                </a>
                                <a href="javascript:;" class="dropdown-item js-delete" data-url="{{route('core.file.delete',['file'=>path_base($file)])}}">
                                    <i class="fa fa-trash fa-fw text-primary"></i> {{trans('core::file.delete')}}
                                </a>
                            </div>                            
                        </div>
                                                       
                    </td>
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
            {{trans('developer::migration.path')}}: {{realpath($path)}}
        </div>
        <div class="footer-text">
            <a data-toggle="collapse" href="#collapse-terminal">
                <i class="fa fa-fw fa-terminal"></i> {{trans('developer::migration.artisan')}}
            </a>
        </div>  
    </div>
    <div class="collapse" id="collapse-terminal">
        <div class="main-footer">
            <div class="footer-text mr-auto">
                {{trans('developer::migration.create')}}: php artisan module:make-migration test {{$module}}
            </div>
            <a href="javascript:;" class="btn btn-primary js-open" data-url="{{route('developer.migration.create',[$name])}}" data-width="800" data-height="300">
                <i class="fa fa-plus fa-fw"></i> {{trans('developer::migration.create')}}
            </a>        
        </div>
        <div class="main-footer">
            <div class="footer-text mr-auto">
                {{trans('developer::migration.migrate')}}: php artisan module:migrate {{$module}}
            </div>
            <a href="javascript:;" class="btn btn-danger js-confirm" data-url="{{route('developer.migration.execute',[$name,'migrate'])}}" data-confirm="{{trans('developer::migration.migrate.tips')}}">
                <i class="fa fa-share fa-fw"></i> {{trans('developer::migration.migrate')}}
            </a>
        </div>
        <div class="main-footer">
            <div class="footer-text mr-auto">
                {{trans('developer::migration.rollback')}}: php artisan module:migrate-rollback {{$module}}
            </div>
            <a href="javascript:;" class="btn btn-danger js-confirm" data-url="{{route('developer.migration.execute',[$name,'rollback'])}}" data-confirm="{{trans('developer::migration.rollback.tips')}}">
                <i class="fa fa-reply fa-fw"></i> {{trans('developer::migration.rollback')}}
            </a>
        </div>
        <div class="main-footer">
            <div class="footer-text mr-auto">
                {{trans('developer::migration.reset')}}: php artisan module:migrate-reset {{$module}}
            </div>
            <a href="javascript:;" class="btn btn-danger js-confirm" data-url="{{route('developer.migration.execute',[$name,'reset'])}}" data-confirm="{{trans('developer::migration.reset.tips')}}">
                <i class="fa fa-reply-all fa-fw"></i> {{trans('developer::migration.reset')}}
            </a>
        </div>    
        <div class="main-footer">
            <div class="footer-text mr-auto">
                {{trans('developer::migration.refresh')}}: php artisan module:migrate-refresh {{$module}}
            </div>
            <a href="javascript:;" class="btn btn-danger js-confirm" data-url="{{route('developer.migration.execute',[$name,'refresh'])}}" data-confirm="{{trans('developer::migration.refresh.tips')}}">
                <i class="fa fa-sync fa-fw"></i> {{trans('developer::migration.refresh')}}
            </a>
        </div>
    </div> 
</div>

@endsection
