<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>{{$title ?? ''}} @yield('title')</title>
    <link rel="stylesheet" href="{{Theme::asset('admin:css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('admin:css/font-awesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('admin:css/global.css')}}" rel="stylesheet">
    <style type="text/css">
    html,body{width:100%;height:100%;padding:0;margin:0;}
    </style>    
</head>
<body>
    <div class="d-flex full-height">        
                
        <div class="jumbotron bg-primary text-white full-width align-self-center text-center pos-r">           
            <h1>{{config('zotop.name')}}</h1>
            <p>{{config('zotop.description')}}</p>
            <div class="p-3">
                <a href="{{config('zotop.homepage')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-globe fa-fw"></i> Homepage
                </a>
                &nbsp;
                <a href="javascript:;" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-circle-o fa-fw"></i> {{config('zotop.version')}}
                </a>
                &nbsp;
                <a href="{{config('zotop.github')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-github fa-fw"></i> Github
                </a>
                &nbsp;
                <a href="{{config('zotop.document')}}" class="btn btn-outline text-white" target="_blank">
                    <i class="fa fa-book fa-fw"></i> Document
                </a>                
                &nbsp;
                <a href="{{config('zotop.help')}}" class="btn btn-outline text-white" target="_blank">
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

    </div>
</body>
</html>
