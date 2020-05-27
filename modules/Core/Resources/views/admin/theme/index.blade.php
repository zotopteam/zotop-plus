@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">            
            <a href="{{route('core.theme.publish')}}" class="btn btn-success js-post" title="{{trans('core::theme.publish.tooltip')}}">
                <i class="fa fa-sync fa-fw"></i> {{trans('core::theme.publish')}}
            </a>
            <a href="{{route('core.theme.upload')}}" class="btn btn-primary btn-upload d-none">
                <i class="fa fa-upload fa-fw"></i> {{trans('core::theme.upload')}}
            </a>
        </div>           
    </div>
    <div class="main-body scrollable">
        <div class="grid grid-xl p-3">
            @foreach($themes as $theme)
            <div class="card card-theme">
                <div class="image pos-r">
                    <label class="badge badge-warning pos-a pos-r-0 m-2">
                        {{trans("core::theme.type.{$theme->type}")}}
                    </label>
                    <img class="card-img-top img-fluid" src="{{preview($theme->path.'/theme.jpg',600,400,'fit')}}" width="600px" height="400px">
                </div>
                <div class="card-body">
                    <div class="card-title d-flex flex-row">
                        <h4 class="mr-auto text-truncate">{{$theme->getTitle()}}</h4>
                        <small class="py-1 ml-3">{{$theme->getVersion()}}</small>
                    </div>
                    <div class="card-text text-truncate text-truncate-2 d-none">{{$theme->getDescription()}}</div>
                    <div class="card-text manage">
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
        {{$description}}
    </div>
</div>
@endsection
