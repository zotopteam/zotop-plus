<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>{{$title ?? ''}} @yield('title') {{config('site.title') ?: config('site.name')}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="description" content="{{config('site.description')}}">
    <meta name="keywords" content="{{config('site.keywords')}}">
    <meta name="author" content="{{config('site.name')}}">
    <meta name="robots" content="index,follow">    
    <link href="{{config('site.favicon') ?: Theme::asset('favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <link href="{{Theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{Theme::asset('css/fontawesome.min.css')}}" rel="stylesheet">
    <link href="{{Theme::asset('css/global.css')}}" rel="stylesheet">
    @stack('css')
</head>
<body>
    <header class="global-header bg-primary fixed-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand d-flex" href="{{url('/')}}">
                    @if ($logo = config('site.logo'))
                        <img src="{{$logo}}" class="site-logo align-self-center">
                    @endif
                    <span class="site-name align-self-center">{{config('site.name')}}</span>
                </a>
                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    {content:navbar view="content::tag.navbar"}
                    <form class="form-inline ml-auto" action="{{route('content.search')}}">
                        <div class="input-group">
                            <input class="form-control bg-light" type="search" name="keywords" value="{{request('keywords')}}" placeholder="Search" aria-label="Search" required="required">
                            <div class="input-group-append">
                                <button class="btn btn-light" type="submit"><i class="fa fa-search text-primary"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </nav>
        </div>
    </header> 
    <div class="jumbotron bg-primary text-white full-width align-self-center text-center pos-r">
        <h1>{{config('site.title')}}</h1>
        <p>{{config('site.description')}}</p>
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
    
    
    <section class="global-body">
        @yield('content')
    </section>
    
    <footer class="global-footer">
        <div class="container text-xs d-flex">
            <div class="copyright">
                {{config('site.copyright') ?: 'Copyright © 2018-2020 All Rights Reserved'}}
            </div>
            <div class="powerby ml-auto mr-1">
                <span class="badge badge-primary">
                    Power By <a class="text-reset" href="{{config('zotop.homepage')}}" target="_blank">{{config('zotop.name')}} v{{config('zotop.version')}}</a>
                </span>
            </div>
            <div class="theme-info text-xs">
                <span class="badge badge-success">{{Theme::getTitle()}} {{Theme::getVersion()}}</span>
            </div>
        </div>
    </footer>

    <script src="{{Theme::asset('js/jquery.min.js')}}"></script>
    <script src="{{Theme::asset('js/popper.min.js')}}"></script>   
    <script src="{{Theme::asset('js/bootstrap.min.js')}}"></script>
    <script src="{{Theme::asset('js/global.js')}}"></script>
    @stack('js')
</body>
</html>
