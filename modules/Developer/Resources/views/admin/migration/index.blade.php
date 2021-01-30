@extends('layouts.master')

@section('content')
    @include('developer::module.side')
    <div class="main">
        <div class="main-header">
            <div class="main-title mr-auto">
                {{$title}}
            </div>
            <div class="main-action">
                <a href="javascript:;" class="btn btn-primary js-open"
                   data-url="{{route('developer.migration.create',[$module])}}" data-width="800" data-height="360">
                    <i class="fa fa-plus fa-fw"></i> {{trans('developer::migration.create')}}
                </a>
                <div class="btn-group">
                    <a href="javascript:;" class="btn btn-danger js-confirm"
                       data-url="{{route('developer.migration.execute',[$module,'migrate'])}}"
                       data-confirm="{{trans('developer::migration.migrate.tips')}}">
                        <i class="fa fa-share fa-fw"></i> {{trans('developer::migration.migrate')}}
                    </a>
                    <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:;" class="dropdown-item js-confirm"
                           data-url="{{route('developer.migration.execute',[$module,'rollback'])}}"
                           data-confirm="{{trans('developer::migration.rollback.tips')}}">
                            <i class="dropdown-item-icon fa fa-reply fa-fw"></i>
                            <b class="dropdown-item-text">{{trans('developer::migration.rollback')}}</b>
                        </a>
                        <a href="javascript:;" class="dropdown-item js-confirm"
                           data-url="{{route('developer.migration.execute',[$module,'reset'])}}"
                           data-confirm="{{trans('developer::migration.reset.tips')}}">
                            <i class="dropdown-item-icon fa fa-reply-all fa-fw"></i>
                            <b class="dropdown-item-text">{{trans('developer::migration.reset')}}</b>
                        </a>
                        <a href="javascript:;" class="dropdown-item js-confirm"
                           data-url="{{route('developer.migration.execute',[$module,'refresh'])}}"
                           data-confirm="{{trans('developer::migration.refresh.tips')}}">
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
                        <td class="text-center">{{trans('developer::migration.migrated')}}</td>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($files as $file)
                        <tr>
                            <td width="1%" class="pr-2">
                                <div class="fa fa-file fa-2x text-primary"></div>
                            </td>
                            <td class="pl-2">
                                <div class="title">{{$file->getFilename()}} </div>
                                <div class="description">
                                    {{$file->getRealPath()}}
                                </div>
                            </td>

                            <td class="manage manage-hover text-right">
                                @if(! in_array(File::name($file), $migrations))
                                    <a href="javascript:;" class="manage-item js-open"
                                       data-url="{{route('core.file.editor',['file'=>path_base($file)])}}"
                                       data-width="80%" data-height="80%">
                                        <i class="fa fa-pen-square fa-fw text-primary"></i> {{trans('master.edit')}}
                                    </a>

                                    <a href="javascript:;" class="manage-item js-delete"
                                       data-url="{{route('core.file.delete',['file'=>path_base($file)])}}">
                                        <i class="fa fa-trash fa-fw text-primary"></i> {{trans('master.delete')}}
                                    </a>
                                @endif
                            </td>
                            <td>{{date('Y-m-d H:i:s',$file->getMTime())}}</td>
                            <td class="text-center">
                                @if(in_array(File::name($file), $migrations))
                                    <i class="fa fa-check-circle fa-2x text-success"></i>
                                @else

                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            @else
                <div class="nodata">{{trans('master.nodata')}}</div>
            @endif
        </div><!-- main-body -->
        <div class="main-footer">
            <div class="footer-text mr-auto">
                {{trans('developer::developer.position')}}: {{$path}}
            </div>
            <div class="footer-text">
                <a data-toggle="collapse" href="#collapse-terminal">
                    <i class="fa fa-fw fa-terminal"></i> {{trans('developer::developer.artisan')}}
                </a>
            </div>
        </div>
        <div class="collapse" id="collapse-terminal">
            <div class="main-footer">
                <div class="footer-text mr-auto">
                    {{trans('developer::migration.create')}}: php artisan module:make-migration test {{$module}}
                </div>
                <a href="javascript:;" class="btn btn-primary js-open"
                   data-url="{{route('developer.migration.create',[$module])}}" data-width="800" data-height="300">
                    <i class="fa fa-plus fa-fw"></i> {{trans('developer::migration.create')}}
                </a>
            </div>
            <div class="main-footer">
                <div class="footer-text mr-auto">
                    {{trans('developer::migration.migrate')}}: php artisan module:migrate {{$module}}
                </div>
                <a href="javascript:;" class="btn btn-danger js-confirm"
                   data-url="{{route('developer.migration.execute',[$module,'migrate'])}}"
                   data-confirm="{{trans('developer::migration.migrate.tips')}}">
                    <i class="fa fa-share fa-fw"></i> {{trans('developer::migration.migrate')}}
                </a>
            </div>
            <div class="main-footer">
                <div class="footer-text mr-auto">
                    {{trans('developer::migration.rollback')}}: php artisan module:migrate-rollback {{$module}}
                </div>
                <a href="javascript:;" class="btn btn-danger js-confirm"
                   data-url="{{route('developer.migration.execute',[$module,'rollback'])}}"
                   data-confirm="{{trans('developer::migration.rollback.tips')}}">
                    <i class="fa fa-reply fa-fw"></i> {{trans('developer::migration.rollback')}}
                </a>
            </div>
            <div class="main-footer">
                <div class="footer-text mr-auto">
                    {{trans('developer::migration.reset')}}: php artisan module:migrate-reset {{$module}}
                </div>
                <a href="javascript:;" class="btn btn-danger js-confirm"
                   data-url="{{route('developer.migration.execute',[$module,'reset'])}}"
                   data-confirm="{{trans('developer::migration.reset.tips')}}">
                    <i class="fa fa-reply-all fa-fw"></i> {{trans('developer::migration.reset')}}
                </a>
            </div>
            <div class="main-footer">
                <div class="footer-text mr-auto">
                    {{trans('developer::migration.refresh')}}: php artisan module:migrate-refresh {{$module}}
                </div>
                <a href="javascript:;" class="btn btn-danger js-confirm"
                   data-url="{{route('developer.migration.execute',[$module,'refresh'])}}"
                   data-confirm="{{trans('developer::migration.refresh.tips')}}">
                    <i class="fa fa-sync fa-fw"></i> {{trans('developer::migration.refresh')}}
                </a>
            </div>
        </div>
    </div>

@endsection
