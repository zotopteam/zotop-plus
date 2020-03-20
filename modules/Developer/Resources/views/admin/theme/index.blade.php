@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('developer.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>         
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="javascript:;" data-url="{{route('developer.theme.create')}}" data-width="800" data-height="300" class="btn btn-primary js-open">
                <i class="fa fa-plus"></i> {{trans('developer::theme.create')}}
            </a>                 
            <a href="{{route('core.theme.publish')}}" class="btn btn-success js-post" title="{{trans('core::theme.publish.tooltip')}}">
                <i class="fa fa-sync fa-fw"></i> {{trans('core::theme.publish')}}
            </a>
        </div>           
    </div>
    <div class="main-body scrollable">
        <div class="grid grid-lg p-3">
            @foreach($themes as $theme)
            <div class="card card-theme">
                <div class="image bg-image-preview pos-r">
                    <label class="badge badge-warning pos-a pos-r-0 m-2">
                        {{trans("core::theme.type.{$theme->type}")}}
                    </label>
                    <img class="card-img-top img-fluid" src="{{preview($theme->path.'/theme.jpg',600,400)}}">
                </div>
                <div class="card-body">
                    <div class="card-title d-flex flex-row">
                        <h4 class="mr-auto text-truncate">{{$theme->getTitle()}}</h4>
                        <small class="py-1 ml-3">{{$theme->getVersion()}}</small>
                    </div>
                    <div class="card-text text-truncate text-truncate-2 d-none">{{$theme->getDescription()}}</div>
                    <div class="card-text manage">
                        <a href="{{route('developer.theme.files', [$theme])}}" class="manage-item">
                            <i class="far fa-file fa-fw"></i> {{trans('developer::theme.files')}}
                        </a>
                        <a href="{{route('core.theme.publish', [$theme])}}" class="manage-item js-post" title="{{trans('core::theme.publish.tooltip')}}">
                            <i class="fa fa-sync fa-fw"></i> {{trans('core::theme.publish')}}
                        </a>
                        <a href="{{route('core.theme.delete', [$theme])}}" class="manage-item js-delete">
                            <i class="fa fa-times fa-fw"></i> {{trans('master.delete')}}
                        </a>                           
                    </div>                                                          
                </div>
            </div>
            @endforeach                    
        </div>             
    </div>
    <div class="main-footer">
    </div>
</div>
@endsection
