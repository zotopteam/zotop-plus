<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>{{$title or ''}} @yield('title')</title>
    <link rel="stylesheet" href="{{Theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('css/font-awesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('css/global.css')}}" rel="stylesheet">
    @stack('css')
</head>
<body>
    <div class="jumbotron bg-primary text-white full-width align-self-center text-center pos-r">
        <h1>{{config('site.name')}}</h1>
        <p>{{config('site.slogan')}}</p>
        <div class="p-3">
            <a href="{{config('app.homepage')}}" class="btn btn-outline text-white" target="_blank">
                <i class="fa fa-globe fa-fw"></i> Homepage
            </a>

            <a href="javascript:;" class="btn btn-outline text-white" target="_blank">
                <i class="fa fa-circle-o fa-fw"></i> {{config('app.version')}}
            </a>

            <a href="{{config('app.github')}}" class="btn btn-outline text-white" target="_blank">
                <i class="fa fa-github fa-fw"></i> Github
            </a>

            <a href="{{config('app.document')}}" class="btn btn-outline text-white" target="_blank">
                <i class="fa fa-book fa-fw"></i> Document
            </a>

            <a href="{{config('app.help')}}" class="btn btn-outline text-white" target="_blank">
                <i class="fa fa-question-circle fa-fw"></i> Help
            </a>
        </div>
        <svg class="animation-wave" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none">
            <defs>
                <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
            </defs>
            <g class="parallax">
                <use xlink:href="#gentle-wave" x="50" y="0" fill="rgba(255,255,255,.5)"></use>
                <use xlink:href="#gentle-wave" x="50" y="3" fill="rgba(255,255,255,.5)"></use>
                <use xlink:href="#gentle-wave" x="50" y="6" fill="rgba(255,255,255,.5)"></use>
            </g>
        </svg>
    </div>

    @yield('content')

    
    <script src="{{Theme::asset('js/jquery.min.js')}}"></script>
    <script src="{{Theme::asset('js/tether.min.js')}}"></script>   
    <script src="{{Theme::asset('js/bootstrap.min.js')}}"></script>
    <script src="{{Theme::asset('js/global.js')}}"></script>
    @stack('js')
</body>
</html>
