@extends('layouts.master')

@section('content')
@include('developer::module.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            @if ($module->isInstalled())
                <a href="{{route('core.module.publish', [$module])}}" class="btn btn-success js-post" title="{{trans('core::module.publish.tooltip')}}"><i class="fa fa-sync fa-fw"></i> {{trans('core::module.publish')}}</a>                 
                @if ($module->isEnabled())
                <a href="javascript:;" data-url="{{route('core.module.disable', [$module])}}" class="btn btn-warning js-post"> <i class="fa fa-times-circle"></i> {{trans('master.disable')}}</a>
                @else
                <a href="javascript:;" data-url="{{route('core.module.enable', [$module])}}" class="btn btn-success js-post"> <i class="fa fa-check-circle"></i> {{trans('master.enable')}}</a>            
                @endif
                @if ($module->getVersion() < $module->getOriginalVersion())
                <a href="javascript:;" data-url="{{route('core.module.upgrade', [$module])}}" class="btn btn-primary js-post"> <i class="fa fa-arrow-up"></i> {{trans('core::module.upgrade')}}</a>                
                @endif           
                <a href="javascript:;" data-url="{{route('core.module.uninstall', [$module])}}" class="btn btn-danger js-post"> <i class="fa fa-trash"></i> {{trans('core::module.uninstall')}}</a>
            @else
                <a href="javascript:;" data-url="{{route('core.module.install', [$module])}}" class="btn btn-success js-post"> <i class="fa fa-wrench"></i> {{trans('core::module.install')}}</a>            
                <a href="javascript:;" data-url="{{route('core.module.delete', [$module])}}" class="btn btn-danger js-post"> <i class="fa fa-times"></i> {{trans('core::module.delete')}}</a>            
            @endif
        </div>           
    </div>
    <div class="main-body scrollable">

        <table class="table table-hover">
            <tr>
                <td class="left" width="15%">{{trans('developer::module.name.label')}}</td>
                <td class="right">{{$module->getStudlyName()}}</td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.title.label')}}</td>
                <td class="right">
                    {{$module->getTitle()}}
                    <a href="javascript:;" title="{{trans('master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$module,'title'])}}" data-value="{{$module->title}}" data-prompt="{{trans('developer::module.title.label')}}">
                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                    </a> 

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.title.help',[strtolower($module)])}}</div>                   
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.description.label')}}</td>
                <td class="right">
                    {{$module->getDescription()}}
                    <a href="javascript:;" title="{{trans('master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$module,'description'])}}" data-value="{{$module->description}}" data-prompt="{{trans('developer::module.description.label')}}" data-type="textarea">
                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                    </a>

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.description.help',[strtolower($module)])}}</div>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.version.label')}}</td>
                <td class="right">
                    {{$module->getOriginalVersion()}}
                    <a href="javascript:;" title="{{trans('master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$module,'version'])}}" data-value="{{$module->version}}" data-prompt="{{trans('developer::module.version.label')}}">
                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                    </a>
                </td>
            </tr>              
            <tr>
                <td class="left" width="15%">{{trans('developer::module.order.label')}}</td>
                <td class="right">
                    {{$module->getOrder()}}
                    <a href="javascript:;" title="{{trans('master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$module,'order'])}}" data-value="{{$module->getOrder(false)}}" data-prompt="{{trans('developer::module.order.label')}}">
                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                    </a>
                    <div class="text-sm text-muted pt-3">{{trans('developer::module.order.help')}}</div>
                </td>
            </tr>            
            <tr>
                <td class="left" width="15%">{{trans('developer::module.author.label')}}</td>
                <td class="right">
                    {{$module->getAuthor()}}
                    <a href="javascript:;" title="{{trans('master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$module,'author'])}}" data-value="{{$module->getAuthor()}}" data-prompt="{{trans('developer::module.author.label')}}">
                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                    </a>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.email.label')}}</td>
                <td class="right">
                    {{$module->getEmail()}}
                    <a href="javascript:;" title="{{trans('master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$module,'email'])}}" data-value="{{$module->getEmail()}}" data-prompt="{{trans('developer::module.email.label')}}">
                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                    </a>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.homepage.label')}}</td>
                <td class="right">
                    {{$module->getHomepage()}}
                    <a href="javascript:;" title="{{trans('master.edit')}}" class="btn btn-sm js-prompt" data-url="{{route('developer.module.update',[$module,'homepage'])}}" data-value="{{$module->getHomepage()}}" data-prompt="{{trans('developer::module.homepage.label')}}">
                        <i class="fa fa-edit"></i> {{trans('master.edit')}}
                    </a>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.seeders.label')}}</td>
                <td class="right">
                    @if ($seeders = $module->getSeeders())
                        @foreach($seeders as $k=>$v)
                        <div class="py-1">{{$v}}</div>
                        @endforeach
                    @else
                    {{trans('master.nodata')}}
                    @endif

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.seeders.help',[$module->getPath('module.json')])}}</div>
                </td>
            </tr>                                                              
            <tr>
                <td class="left" width="15%">{{trans('developer::module.providers.label')}}</td>
                <td class="right">
                    @if ($providers = $module->getProviders())
                        @foreach($providers as $k=>$v)
                        <div class="py-1">{{$v}}</div>
                        @endforeach
                    @else
                    {{trans('master.nodata')}}
                    @endif

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.providers.help',[$module->getPath('module.json')])}}</div>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.aliases.label')}}</td>
                <td class="right">
                    @if ($aliases = $module->getAliases())
                    <table class="table table-sm table-inside bg-transparent">
                        @foreach($aliases as $k=>$v)
                        <tr>
                            <td class="px-0 border-0">{{$k}}</td>
                            <td class="border-0">{{$v}}</td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    {{trans('master.nodata')}}
                    @endif

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.aliases.help',[$module->getPath('module.json')])}}</div>
                </td>
            </tr>
            <tr>
                <td class="left" width="15%">{{trans('developer::module.files.label')}}</td>
                <td class="right">
                    @if ($files = $module->getFiles())
                    <table class="table table-sm table-inside bg-transparent">
                        @foreach($files as $k=>$v)
                        <tr>
                            <td class="px-0 border-0">{{$v}}</td>
                        </tr>
                        @endforeach
                    </table>
                    @endif

                    <div class="text-sm text-muted pt-3">{{trans('developer::module.files.help',[$module->getPath('module.json')])}}</div>
                </td>
            </tr>                        
        </table>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('developer::module.path')}}: {{$module->getPath()}}
        </div>
    </div>
</div>


@endsection

@push('js')

@endpush
