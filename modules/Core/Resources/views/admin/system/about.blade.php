@extends('core::layouts.master')

@section('content')
<div class="main scrollable">
    
    <div class="jumbotron bg-primary text-white text-center">
        <div class="container-fluid">
            <h1>{{config('app.title')}}</h1>
            <p>{{config('app.description')}}</p>
            <div class="p-3">
                <a href="{{config('app.homepage')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-globe fa-fw"></i> Homepage
                </a>
                &nbsp;
                <a href="javascript:;" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-circle-o fa-fw"></i> {{config('app.version')}}
                </a>
                &nbsp;
                <a href="{{config('app.github')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-github fa-fw"></i> Github
                </a>
                &nbsp;
                <a href="{{config('app.document')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-book fa-fw"></i> Document
                </a>                
                &nbsp;
                <a href="{{config('app.help')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-question-circle fa-fw"></i> Help
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <dl>
            <dt>{{trans('core::master.thanks',[config('app.name'),config('app.version')])}}</dt>
            <dd>{{trans('core::system.about.version')}} &nbsp; {{config('app.version')}} ({{config('app.release')}}) </dd>
            <dd>{{trans('core::system.about.developer')}} &nbsp; Hankx.Chen, Allen.Qu</dd>
            <dd>{{trans('core::system.about.homepage')}} &nbsp; <a href="{{config('app.homepage')}}" target="_balnk">{{config('app.homepage')}}</a></dd>
        </dl>
    </div>
@endsection