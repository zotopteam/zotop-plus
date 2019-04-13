{{-- title:后台对话框布局模板 --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf_token" content="{{ csrf_token() }}"/>
    <title>{{config('site.name')}} {{$title ?? ''}} {{config('zotop.name')}} </title>
    <link href="{{Theme::asset('favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="{{Theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('css/fontawesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('css/jquery.dialog.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{Theme::asset('css/global.css')}}" rel="stylesheet">
    @stack('css')
</head>
<body class="dialog {{app('current.module')}}-{{app('current.controller')}}-{{app('current.action')}}">
    <section class="global-body">
        @yield('content')
    </section>
    <script src="{{Theme::asset('js/jquery.min.js')}}"></script>
    <script src="{{Theme::asset('js/popper.min.js')}}"></script>    
    <script src="{{Theme::asset('js/bootstrap.min.js')}}"></script>
    <script src="{{Theme::asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{Theme::asset('js/jquery.nicescroll.min.js')}}"></script> 
    <script src="{{Theme::asset('js/jquery.dialog.js')}}"></script>
    <script src="{{Theme::asset('js/cms.js')}}"></script>
    <script src="{{Theme::asset('js/global.js')}}"></script>
    @if(!App::isLocale('en'))
    <script src="{{Theme::asset('lang/'.App::getLocale().'/jquery.validate.js')}}"></script>
    @endif
    <script type="text/javascript">
    var $dialog = $.dialog();
    </script>
    @stack('js') 
</body>
</html>
