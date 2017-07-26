<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <title>{{$title or ''}} @yield('title')</title>
    <link rel="stylesheet" href="{{theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{theme::asset('css/font-awesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{theme::asset('css/global.css')}}" rel="stylesheet">
    @stack('css')
</head>
<body>
    @yield('content')
    
    <script src="{{theme::asset('js/jquery.min.js')}}"></script>
    <script src="{{theme::asset('js/bootstrap.min.js')}}"></script>
    <script src="{{theme::asset('js/global.js')}}"></script>
    @stack('js')
</body>
</html>