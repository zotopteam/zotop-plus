<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>{{$title ?? ''}} @yield('title') {{config('site.name')}}</title>
    <link href="{{config('site.favicon') ?: Theme::asset('favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="{{Theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('css/fontawesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('css/global.css')}}" rel="stylesheet">
    @stack('css')
</head>
<body>
    <header class="global-header">
        <div class="jumbotron bg-primary text-white full-width align-self-center text-center pos-r">
            <h1>{{config('site.name')}}</h1>
            <p>{{config('site.slogan')}}</p>
            <div class="p-3">
                {content:navbar view="content::tag.navbar"}
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
    </header>
    
    <section class="global-body">
        @yield('content')
    </section>
    
    <footer class="global-footer">
        <div class="container">
            <div class="copyright">
                {{config('site.copyright')}}
            </div>
            <div class="powerby text-xs">
                Power By <a href="{{config('zotop.homepage')}}" target="_blank">{{config('zotop.name')}} v{{config('zotop.version')}}</a>
                {{Theme::getTitle()}}
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
