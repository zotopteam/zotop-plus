@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">            
            <a href="{{route('core.themes.publish')}}" class="btn btn-success js-post" title="{{trans('core::themes.publish.tooltip')}}">
                <i class="fa fa-refresh fa-fw"></i> {{trans('core::themes.publish')}}
            </a>
            <a href="{{route('core.themes.upload')}}" class="btn btn-primary btn-upload">
                <i class="fa fa-upload fa-fw"></i> {{trans('core::themes.upload')}}
            </a>
        </div>           
    </div>
    <div class="main-body scrollable">
        <div class="container-fluid">
            <div class="row">
            @foreach($themes as $theme)
                <div class="col-3">                
                    <div class="card card-theme my-3">
                        <div class="image">
                            <img class="card-img-top img-fluid" src="{{preview($theme->path.'/theme.jpg',300,200)}}">
                        </div>
                        <div class="card-body">
                            <div class="card-title d-flex flex-row">
                                <h4 class="mr-auto">{{$theme->title}}</h4>
                                <small>version:{{$theme->version}}</small>
                            </div>
                            <div class="card-text text-overflow text-overflow-2 d-none">{{$theme->description}}</div>
                            <div class="card-text manage">
                                <a href="{{route('core.themes.files', [$theme->name,'dir'=>'./views'])}}" class="manage-item">
                                    <i class="fa fa-file-text fa-fw"></i> {{trans('core::themes.views')}}
                                </a>
                                <a href="{{route('core.themes.files', [$theme->name,'dir'=>'./assets'])}}" class="manage-item">
                                    <i class="fa fa-file-text-o fa-fw"></i> {{trans('core::themes.assets')}}
                                </a>
                                <a href="{{route('core.themes.publish', [$theme->name])}}" class="manage-item js-post" title="{{trans('core::themes.publish.tooltip')}}">
                                    <i class="fa fa-refresh fa-fw"></i> {{trans('core::themes.publish')}}
                                </a>
                            </div>                                                          
                        </div>
                    </div>
                </div>
            @endforeach                    
            </div>
        </div>             
    </div>
    <div class="main-footer">
        {{$description}}
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
