@extends('core::layouts.master')

@section('content')
<div class="full-width">
    <div class="jumbotron bg-primary text-white text-center m-0 pos-r">
        <div class="container-fluid">
            <h1>{{trans('core::system.environment.title')}}</h1>
            <p>{{trans('core::system.environment.description')}}</p>
        </div>
        <svg class="animation-wave" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none">
            <defs>
                <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
            </defs>
            <g class="parallax">
                <use xlink:href="#gentle-wave" x="50" y="0" fill="rgba(255,255,255,.3)"></use>
                <use xlink:href="#gentle-wave" x="50" y="3" fill="rgba(255,255,255,.3)"></use>
                <use xlink:href="#gentle-wave" x="50" y="6" fill="rgba(255,255,255,.3)"></use>
            </g>
        </svg>           
    </div>

    <div class="p-3">
        <div class="card mb-3">
            <div class="card-header">
                {{trans('core::system.environment.server')}}
                <p class="card-text text-muted">
                    {{trans('core::system.environment.server.description')}}
                </p>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                    @foreach($server as $key=>$val)
                    <tr>
                        <td>{{trans("core::system.environment.server.{$key}")}}</td>
                        <td>{{$val}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>                
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                {{trans('core::system.environment.files')}}
                <p class="card-text text-muted">
                    {{trans('core::system.environment.files.description')}}
                </p>                
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                    @foreach($filesystem as $fs)
                    <tr>
                        <td><i class="fa fa-fw {{$fs['icon']}} text-warning"></i> {{path_base($fs['path'])}}</td>
                        <td>{{$fs['perms']}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>                   
            </div>
        </div>        
    </div>
</div>
@endsection
