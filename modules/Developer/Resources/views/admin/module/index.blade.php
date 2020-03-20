@extends('layouts.master')

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
            <a href="javascript:;" data-url="{{route('developer.module.create')}}" data-width="800" data-height="300" class="btn btn-primary js-open">
                <i class="fa fa-plus"></i> {{trans('developer::module.create')}}
            </a>
            <a href="{{route('core.module.publish')}}" class="btn btn-success js-post" title="{{trans('core::module.publish.tooltip')}}">
                <i class="fa fa-sync fa-fw"></i> {{trans('core::module.publish')}}
            </a>
        </div>               
    </div>
    <div class="main-body scrollable">
        
        <div class="grid grid-sm p-3">
            @foreach ($modules as $module)
                <div class="text-center">
                    <a class="shortcut cur-p" href="{{route('developer.module.show',$module->name)}}">
                        <img src="{{preview($module->getPath('module.png'), 128, 128)}}">
                        <div class="mt-1">
                            {{$module->getTitle()}}
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            <i class="fa fa-circle-o fa-fw text-primary"></i> {{trans('developer::module.description')}}
        </div>
    </div>
</div>
@endsection
