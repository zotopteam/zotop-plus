<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf_token" content="{{ csrf_token() }}"/>
    <title>{{config('site.name')}} {{$title or ''}} {{config('app.name')}} </title>
    <link href="{{theme::asset('favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="{{theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{theme::asset('css/fontawesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{theme::asset('css/jquery.dialog.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{theme::asset('css/global.css')}}" rel="stylesheet">
    @stack('css')
</head>
<body class="{{app('current.module')}}-{{app('current.controller')}}-{{app('current.action')}}">
    <header class="global-header">
        <nav class="row" role="navigation">
            <div class="col-sm-6 col-md-7 col-lg-8">
                <ul class="nav global-navbar tabdropable">
                    <li class="brand dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{config('app.name')}}</a>
                        <div class="dropdown-menu dropdown-start">
                            <div class="shortcuts scrollable">
                                <div class="container-fluid">
                                    <div class="row">
                                        @foreach(Filter::fire('global.start',[]) as $s)
                                        <div class="col-md-2 p-0">
                                            <a href="{{$s['href']}}" class="shortcut shortcut-thumb {{$s['class'] ?? ''}}" target="{{$s['target'] ?? '_self'}}">
                                                <div class="shortcut-icon">
                                                    <i class="{{$s['icon']}}"></i>
                                                    @if(isset($s['badge']))
                                                    <b class="shortcut-badge badge badge-xs badge-danger">{{$s['badge']}}</b>
                                                    @endif       
                                                </div>
                                                <div class="shortcut-text">
                                                    <h2>{{$s['text']}}</h2>
                                                </div>
                                            </a>                                        
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    @foreach(Filter::fire('global.navbar',[]) as $navbar)                    
                    <li class="item {{$navbar['class'] or ''}} {{$navbar['active'] ? 'active' : ''}}">
                        <a href="{{$navbar['href']}}">{{$navbar['text']}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-6 col-md-5 col-lg-4">
                <ul class="nav global-navbar global-tools float-right">
                    
                    @foreach(Filter::fire('global.tools',[]) as $tools)                    
                    <li>
                        <a {!!Html::attributes(array_except($tools,['icon','text']))!!}>
                            @if(isset($tools['icon']))<i class="{{$tools['icon']}} fa-fw"></i>@endif
                            @if(isset($tools['text']))<span class="d-none d-xl-inline-block">{{$tools['text']}}</span>@endif
                        </a>
                    </li>
                    @endforeach
                    
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user-circle"></i> <span class="hidden-md-down">{{Auth::user()->username}}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-primary dropdown-menu-right">
                            <a class="dropdown-item" href="{{route('core.mine.edit')}}">
                                <i class="dropdown-item-icon fa fa-user fa-fw"></i>
                                <b class="dropdown-item-text">{{trans('core::mine.edit')}}</b>
                            </a>
                            <a class="dropdown-item" href="{{route('core.mine.password')}}">
                                <i class="dropdown-item-icon fa fa-key fa-fw"></i>
                                <b class="dropdown-item-text">{{trans('core::mine.password')}}</b>
                            </a>
                            <a class="dropdown-item d-none" href="{{route('core.mine.permission')}}">
                                <i class="dropdown-item-icon fa fa-sitemap fa-fw"></i>
                                <b class="dropdown-item-text">{{trans('core::mine.permission')}}</b>
                            </a>
                            <a class="dropdown-item d-none" href="{{route('core.mine.log')}}">
                                <i class="dropdown-item-icon fa fa-flag fa-fw"></i>
                                <b class="dropdown-item-text">{{trans('core::mine.log')}}</b>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item js-confirm" href="{{route('admin.logout')}}" data-confirm="{{trans('core::auth.logout.confirm')}}">
                                <i class="dropdown-item-icon fa fa-sign-out-alt fa-fw"></i>
                                <b class="dropdown-item-text">{{trans('core::auth.logout')}}</b>
                            </a>
                        </div>
                    </li>                       
                </ul>                
            </div>
        </nav>
    </header>    
    <section class="global-body">
        @yield('content')
    </section>
    <footer class="global-footer">        
    </footer>

    <script src="{{theme::asset('js/jquery.min.js')}}"></script>
    <script src="{{theme::asset('js/popper.min.js')}}"></script>    
    <script src="{{theme::asset('js/bootstrap.min.js')}}"></script>
    <script src="{{theme::asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{theme::asset('js/jquery.nicescroll.min.js')}}"></script>    
    <script src="{{theme::asset('js/jquery.dialog.js')}}"></script>
    <script src="{{theme::asset('js/cms.js')}}"></script>
    <script src="{{theme::asset('js/global.js')}}"></script>
    @if(!App::isLocale('en'))
    <script src="{{theme::asset('lang/'.App::getLocale().'/jquery.validate.js')}}"></script>
    @endif
    @stack('js')   
</body>
</html>
