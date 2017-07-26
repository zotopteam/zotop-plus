<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf_token" content="{{ csrf_token() }}"/>
    <title>{{config('cms.modules.core.site.name')}} {{$title or ''}} {{config('app.name')}} </title>
    <link href="{{theme::asset('favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="{{theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{theme::asset('css/font-awesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{theme::asset('css/jquery.dialog.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{theme::asset('css/global.css')}}" rel="stylesheet">
    @stack('css')
</head>
<body class="{{app('current.module')}}-{{app('current.controller')}}-{{app('current.action')}}">
    <header class="global-header">
        <nav class="row" role="navigation">
            <div class="col-sm-6 col-md-8 col-lg-9">
                <ul class="nav global-navbar tabdropable">
                    <li class="brand dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CMS</a>
                        <div class="dropdown-menu dropdown-start">
                            <div class="shortcuts scrollable">
                                <div class="container-fluid">
                                    <div class="row">
                                        @foreach(filter::fire('global.start',[]) as $s)
                                        <div class="col-md-2 p-0">
                                            <a href="{{$s['href']}}" class="shortcut shortcut-thumb">
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

                    @foreach(filter::fire('global.navbar',[]) as $navbar)                    
                    <li class="item {{$navbar['class'] or ''}} {{$navbar['active'] ? 'active' : ''}}">
                        <a href="{{$navbar['href']}}">{{$navbar['text']}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <ul class="nav global-navbar float-right">
                    <li class="viewsite">
                        <a href="{{config('cms.modules.core.site.url') ?: route('index')}}" title="{{trans('core::master.viewsite.description',[config('cms.modules.core.site.name')])}}" target="_blank">
                            <i class="fa fa-home fa-fw"></i> <span class="hidden-md-down">{{trans('core::master.viewsite')}}</span>
                        </a>
                    </li>
                    <li class="refresh">
                        <a class="js-post" href="{{route('core.system.refresh')}}" title="{{trans('core::master.refresh.description')}}">
                            <i class="fa fa-magic fa-fw"></i> <span class="hidden-md-down">{{trans('core::master.refresh')}}</span>
                        </a>
                    </li>
                    <li class="dropdown hidden-xs">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user-circle"></i> <span class="hidden-md-down">{{Auth::user()->username}}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="{{route('core.mine.edit')}}"><i class="fa fa-user fa-fw"></i> {{trans('core::mine.edit')}}</a></li>
                            <li><a href="{{route('core.mine.password')}}"><i class="fa fa-edit fa-fw"></i> {{trans('core::mine.password')}}</a></li>
                            <li style="display:none;"><a href="{{route('core.mine.permission')}}"><i class="fa fa-sitemap fa-fw"></i> {{trans('core::mine.permission')}}</a></li>
                            <li style="display:none;"><a href="{{route('core.mine.log')}}"><i class="fa fa-flag fa-fw"></i> {{trans('core::mine.log')}}</a></li>
                            <li><a href="{{route('admin.logout')}}" class="js-confirm" data-confirm="{{trans('core::auth.logout.confirm')}}"><i class="fa fa-sign-out fa-fw"></i> {{trans('core::auth.logout')}}</a></li>
                        </ul>
                    </li>                       
                </ul>                
            </div>
        </nav>
    </header>    
    <section class="global-body scrollable">
        @yield('content')
    </section>
    <footer class="global-footer">        
    </footer>

    <script src="{{theme::asset('js/jquery.min.js')}}"></script>
    <script src="{{theme::asset('js/tether.min.js')}}"></script>    
    <script src="{{theme::asset('js/bootstrap.min.js')}}"></script>
    <script src="{{theme::asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{theme::asset('js/jquery.dialog.js')}}"></script>
    <script src="{{theme::asset('js/cms.js')}}"></script>
    <script src="{{theme::asset('js/global.js')}}"></script>
    @if(!App::isLocale('en'))
    <script src="{{theme::asset('lang/'.App::getLocale().'/jquery.validate.js')}}"></script>
    @endif
    @stack('js')   
</body>
</html>