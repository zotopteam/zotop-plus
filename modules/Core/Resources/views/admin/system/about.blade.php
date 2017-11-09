@extends('core::layouts.master')

@section('content')
<div class="main scrollable bg-primary">
    <div class="jumbotron full-width bg-primary text-white text-center align-self-center">
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
@endsection
