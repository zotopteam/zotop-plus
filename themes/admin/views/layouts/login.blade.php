{{-- title:后台登录页面模板 --}}
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <title>{{$title ?? ''}} @yield('title')</title>
    <link href="{{Theme::asset('favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <link href="{{Theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{Theme::asset('css/fontawesome.min.css')}}" rel="stylesheet">
    <link href="{{Theme::asset('css/global.css')}}" rel="stylesheet">
    <link href="{{Theme::asset('css/login.css')}}" rel="stylesheet">
    @stack('css')
    @action('admin.login.css')
    <script>
        window.cms = @json(Filter::fire('window.cms', []));
    </script>
</head>

<body class="{{app('current.module')}}-{{app('current.controller')}}-{{app('current.action')}}">

    @yield('content')

    <script src="{{Theme::asset('js/jquery.min.js')}}"></script>
    <script src="{{Theme::asset('js/popper.min.js')}}"></script>
    <script src="{{Theme::asset('js/bootstrap.min.js')}}"></script>
    <script src="{{Theme::asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{Theme::asset('js/cms.js')}}"></script>
    <script src="{{Theme::asset('js/global.js')}}"></script>
    @if(!App::isLocale('en'))
    <script src="{{Theme::asset('lang/'.App::getLocale().'/jquery.validate.js')}}"></script>
    @endif
    <script type="text/javascript">
        if (top != self) {
        top.location = self.location;
    }        
    </script>
    @stack('js')
    @action('admin.login.js')
</body>

</html>
