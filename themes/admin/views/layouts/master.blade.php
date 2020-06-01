{{-- title:后台主布局模板 --}}
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <title>{{$title ?? ''}} {{config('site.name')}} {{config('zotop.name')}} </title>
    <link href="{{Theme::asset('favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <link href="{{Theme::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{Theme::asset('css/fontawesome.min.css')}}" rel="stylesheet">
    <link href="{{Theme::asset('css/jquery.dialog.css')}}" rel="stylesheet">
    <link href="{{Theme::asset('css/global.css')}}" rel="stylesheet">
    @stack('css')
    @action('admin.master.css')
    <script>
        window.cms = @json(Filter::fire('window.cms', []));
    </script>
</head>

<body class="{{app('current.module')}}-{{app('current.controller')}}-{{app('current.action')}}">

    <header class="global-header">
        <ul class="nav global-navbar tabdropable">
            <li class="brand dropdown">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    {{config('zotop.name')}}
                </a>
                <div class="dropdown-menu dropdown-start">
                    <div class="grid grid-xs grid-gap-none grid-hover text-center">
                        @foreach(Filter::fire('global.start',[]) as $s)
                        <a href="{{$s['href']}}"
                            class="grid-item rounded text-reset text-decoration-none p-2 {{$s['class'] ?? ''}}"
                            target="{{$s['target'] ?? '_self'}}">
                            <div class="grid-item-icon pos-r">
                                <i class="icon-md f-2 rounded py-2 {{$s['icon']}}"></i>
                                @if(isset($s['badge']))
                                <b class="pos-a mt-n2 ml-n2 badge badge-xs badge-danger">{{$s['badge']}}</b>
                                @endif
                            </div>
                            <div class="grid-item-text text-sm text-truncate">
                                {{$s['text']}}
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </li>

            @foreach(Filter::fire('global.navbar',[]) as $navbar)
            <li class="item {{$navbar['class'] ?? ''}} {{$navbar['active'] ? 'active' : ''}}">
                <a href="{{$navbar['href']}}">{{$navbar['text']}}</a>
            </li>
            @endforeach
        </ul>
        <ul class="nav global-navbar global-tools ml-auto">
            @foreach(Filter::fire('global.tools',[]) as $tools)
            <li class="global-tool">
                <a {!!Html::attributes(array_except($tools,['icon','text','badge','badgeClass']))!!}>
                    @if(isset($tools['badge']))
                    <span
                        class="global-tool-badge badge {{$tools['badgeClass'] ?? 'badge-danger'}} {{$tools['badge'] ? 'd-block' : 'd-none'}}">
                        {{$tools['badge']}}
                    </span>
                    @endif
                    @if(isset($tools['icon']))
                    <i class="global-tool-icon {{$tools['icon']}} fa-fw"></i>
                    @endif
                    @if(isset($tools['text']))
                    <span class="global-tool-text d-none d-xl-inline-block">{{$tools['text']}}</span>@endif
                </a>
            </li>
            @endforeach
            <li class="global-tool dropdown">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="global-tool fa fa-user-circle"></i> <span
                        class="global-tool-text hidden-md-down">{{Auth::user()->username}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
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
                    <a class="dropdown-item js-confirm" href="{{route('admin.logout')}}"
                        data-confirm="{{trans('core::auth.logout.confirm')}}">
                        <i class="dropdown-item-icon fa fa-sign-out-alt fa-fw"></i>
                        <b class="dropdown-item-text">{{trans('core::auth.logout')}}</b>
                    </a>
                </div>
            </li>
        </ul>
    </header>
    <section class="global-body scrollable">
        @yield('content')
    </section>
    <div class="global-footer">
    </div>

    <script src="{{Theme::asset('js/jquery.min.js')}}"></script>
    <script src="{{Theme::asset('js/popper.min.js')}}"></script>
    <script src="{{Theme::asset('js/bootstrap.min.js')}}"></script>
    <script src="{{Theme::asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{Theme::asset('js/jquery.dialog.js')}}"></script>
    <script src="{{Theme::asset('js/cms.js')}}"></script>
    <script src="{{Theme::asset('js/global.js')}}"></script>
    @if(!App::isLocale('en'))
    <script src="{{Theme::asset('lang/'.App::getLocale().'/jquery.validate.js')}}"></script>
    @endif
    @stack('js')
    @action('admin.master.js')
</body>

</html>
