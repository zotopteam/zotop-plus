<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf_token" content="{{ csrf_token() }}"/>
        <title>{{trans('installer.title',[config('app.name')])}}</title>
        <link rel="icon" type="image/png" href="{{ asset('installer/favicon-16x16.png') }}" sizes="16x16"/>
        <link rel="icon" type="image/png" href="{{ asset('installer/favicon-32x32.png') }}" sizes="32x32"/>
        <link rel="icon" type="image/png" href="{{ asset('installer/favicon-96x96.png') }}" sizes="96x96"/>        
        <link href="{{ asset('installer/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="{{ asset('installer/global.css') }}" rel="stylesheet" type="text/css">

        @stack('css')
    </head>
    <body class="d-flex flex-column bg-primary">
        
        <header class="header">
            <nav  class="navbar navbar-expand-lg navbar-dark bg-transparent">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('installer/logo.png') }}" width="30" height="30" class="d-inline-block align-top mr-1" alt="">
                    {{trans('installer.title',[config('app.name')])}}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        @foreach($wizard as $w)
                        <li class="nav-item {{$current == $w ? 'active' : ''}}">
                            <a class="nav-link" href="javascript:;">{{trans("installer.wizard.$w")}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </nav>
        </header>
        
        @yield('content')

        <footer class="footer d-flex">
            
            @yield('wizard')
           
        </footer>

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

        <script src="{{asset('installer/jquery.min.js')}}"></script>
        <script src="{{asset('installer/popper.min.js')}}"></script>   
        <script src="{{asset('installer/bootstrap.min.js')}}"></script>
        <script src="{{asset('installer/jquery.validate.min.js')}}"></script>
        <script src="{{asset('installer/jquery.dialog.js')}}"></script>        
        <script src="{{asset('installer/global.js')}}"></script>
        @stack('js')                  
    </body>
</html>
