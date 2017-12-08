@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('developer.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>    
        <div class="main-title mx-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="javascript:;" data-url="{{route('developer.module.create')}}" data-width="800" data-height="300" class="btn btn-primary js-open"> <i class="fa fa-plus"></i> {{trans('developer::module.create')}}</a>
        </div>        
    </div>
    <div class="main-body scrollable">

        <table class="table table-nowrap table-hover">
            <thead>
                <tr>
                    <td width="20%" colspan="2">{{trans('core::modules.name.label')}}</td>
                    <td width="10%">{{trans('core::modules.version.label')}}</td>
                    <td>{{trans('core::modules.description.label')}}</td>
                    <td class="text-center">{{trans('developer::module.edit')}}</td>
                </tr>
            </thead>        
            <tbody>  
            @foreach($modules as $name=>$module)
                <tr class="item {{$module->active?'active':'disabled'}}">
                    <td width="1%" class="icon icon-sm pr-2">
                        <img src="{{preview($module->getExtraPath('/module.png'), 48, 48)}}">
                    </td>
                    <td class="pl-2">
                        <div class="title"> <b class="text-lg">{{$module->title}}</b> <span class="text-muted">{{$name}}</span></div>
                        <div class="manage">
                            @if($module->installed)
                                @if($module->active)
                                <a href="javascript:;" data-url="{{route('core.modules.disable',[$name])}}" class="manage-item js-confirm"><i class="fa fa-times-circle"></i> {{trans('core::master.disable')}}</a>
                                @else
                                <a href="javascript:;" data-url="{{route('core.modules.enable',[$name])}}" class="manage-item js-confirm"><i class="fa fa-check-circle"></i> {{trans('core::master.active')}}</a>
                                @endif

                                @foreach(filter::fire('modules.manage',[],$module) as $s)
                                <a href="{{$s['href']}}" class="manage-item {{$s['icon']}}"><i class="{{$s['icon']}}"></i>{{$s['text']}}</a>                                        
                                @endforeach
                           
                                <a href="javascript:;" data-url="{{route('core.modules.uninstall',[$name])}}" data-confirm="{{trans('core::modules.uninstall.confirm',[$module->title])}}"  class="manage-item js-confirm"><i class="fa fa-trash"></i> {{trans('core::modules.uninstall')}}</a>
                            @else
                                <a href="javascript:;" data-url="{{route('core.modules.install',[$name])}}" class="manage-item js-confirm"><i class="fa fa-wrench"></i> {{trans('core::modules.install')}}</a>
                                <a href="javascript:;" data-url="{{route('core.modules.delete',[$name])}}" data-confirm="{{trans('core::modules.delete.confirm',[$module->title])}}" class="manage-item js-confirm"><i class="fa fa-times"></i> {{trans('core::master.delete')}}</a>
                            @endif
                        </div>
                    </td>
                    <td width="10%">{{$module->version}}</td>
                    <td>
                        <p>{{$module->description}}</p>
                        <div class="manage text-muted text-xs">
                            @if($module->author)
                            <span class="manage-item">{{$module->author}}</span>
                            @endif

                            @if($module->email)
                            <a class="manage-item" href="mailto:{{$module->email}}">{{$module->email}}</a>
                            @endif

                            @if($module->homepage)
                            <a class="manage-item" href="{{$module->homepage}}" target="_blank">{{$module->homepage}}</a>
                            @endif
                        </div>  
                    </td>
                    <td class="text-center">
                        <a class="btn btn-success rounded-circle" href="{{route('developer.module.show',$module->name)}}">
                            <i class="fa fa-pencil-alt fa-2x"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            <i class="fa fa-circle-o fa-fw text-primary"></i> {{trans('developer::module.description')}}
        </div>
    </div>
</div>
@endsection
