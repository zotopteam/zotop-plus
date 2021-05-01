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
    <link href="{{Theme::asset('favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="{{Theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('css/global.css')}}" rel="stylesheet">
    @stack('css')
</head>
<body>
    <header class="global-header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand mr-5" href="{{url('/')}}">{{config('site.name')}}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{url('/')}}">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Dropdown
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>        
    </header> 


    <section class="global-body">
        @yield('content')
    </section>

    <footer class="global-footer bg-light p-4">
        <div>
            <a class="d-inline-block mr-2" href="#">Link</a>
            <a class="d-inline-block mr-2" href="#">Link</a>
            <a class="d-inline-block mr-2" href="#">Link</a>
            <a class="d-inline-block mr-2" href="#">Link</a>
            <a class="d-inline-block mr-2" href="#">Link</a>
            <a class="d-inline-block mr-2" href="#">Link</a>
        </div>
        <div class="d-flex mt-3">
            <div class="text-muted font-weight-lighter">
            {{config('site.copyright') ?: 'Copyright©2020 All Rights Reserved'}}
            </div>
            <div class="ml-auto">
                <span class="badge badge-primary">
                    Power By <a class="text-reset" href="{{config('zotop.homepage')}}" target="_blank">{{config('zotop.name')}} v{{config('zotop.version')}}</a>
                </span>
            </div>
            <div class="ml-2">
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
