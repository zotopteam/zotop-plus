@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">    
        <div class="main-title">
            {{$title}}
        </div>
    </div>
    <div class="main-body scrollable">        
        <table class="table table-nowrap table-sortable">
            <thead>
                <tr>
                    <td class="text-center" width="5%">{{trans('core::modules.status.label')}}</td>
                    <td width="20%" colspan="2">{{trans('core::modules.name.label')}}</td>
                    <td width="10%">{{trans('core::modules.version.label')}}</td>
                    <td>{{trans('core::modules.description.label')}}</td>
                </tr>
            </thead>        
            <tbody>  
            @foreach($modules as $name=>$module)
                <tr class="item {{$module->active?'active':'disabled'}}">
                    <td class="text-center">
                        <i class="fa fa-2x {{$module->active ? 'fa-check-circle text-success' : 'fa-times-circle'}}"></i>
                    </td>
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
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="main-footer">
        <div class="footer-text">{{trans('core::modules.description')}}</div>
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
