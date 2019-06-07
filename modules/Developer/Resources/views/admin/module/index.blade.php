@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('developer.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
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
                    <td colspan="2">{{trans('core::module.name.label')}}</td>
                    <td width="10%">{{trans('core::module.version.label')}}</td>
                    <td width="40%" c>{{trans('core::module.description.label')}}</td>
                    <td width="10%" class="text-center">{{trans('developer::module.edit')}}</td>
                </tr>
            </thead>        
            <tbody>  
            @foreach($modules as $name=>$module)
                <tr class="item {{$module->active?'active':'disabled'}}">
                    <td width="1%" class="pr-2">
                        <div class="icon icon-md">
                            <img src="{{preview($module->getExtraPath('/module.png'), 48, 48)}}">
                        </div>
                    </td>
                    <td class="pl-2">
                        <div class="title"> <b class="text-lg">{{$module->title}}</b> <span class="text-muted">{{$name}}</span></div>
                        <div class="manage">
                            @foreach(filter::fire('module.manage', [], $module) as $s)
                            <a href="{{$s['href'] ?? 'javascript:;'}}" class="manage-item {{$s['class'] ?? ''}}" {!!Html::attributes(array_except($s,['icon','text','href','class']))!!}>
                                <i class="{{$s['icon'] ?? ''}} fa-fw"></i> {{$s['text']}}
                            </a>
                            @endforeach
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
                        <a class="fa-stack fa-2x text-success" href="{{route('developer.module.show',$module->name)}}">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fa fa-pencil-alt fa-stack-1x fa-inverse"></i>
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
