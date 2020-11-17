@extends('layouts.master')

@section('content')

    <div class="main">
        <div class="main-header d-none">
            <div class="main-title mr-auto">
                {{$title}}
            </div>
        </div>
        <div class="main-body scrollable">
            <div class="grid grid-md grid-hover p-3">
                @foreach(Filter::fire('global.start',[]) as $s)
                    <a href="{{$s['href']}}" class="grid-item flex-row align-items-center p-2 {{$s['class'] ?? ''}}"
                       target="{{$s['target'] ?? '_self'}}">
                        <div class="mr-2 d-flex justify-content-center align-item-center pos-r">
                            @if(isset($s['badge']))
                                <b class="pos-a mt-n1 ml-n1 badge badge-xs badge-danger">{{$s['badge']}}</b>
                            @endif
                            @if (isset($s['icon']))
                                <i class="fw-3 fh-3 {{$s['icon']}} fs-2 rounded text-center py-2"></i>
                            @endif
                            @if (isset($s['image']))
                                <div class="fw-3 fh-3 rounded overflow-hidden">
                                    <img src="{{$s['image']}}" class="img-fluid">
                                </div>
                            @endif
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                            <div class="text-lg text-truncate">{{$s['text']}}</div>
                            <div class="text-sm text-truncate">{{$s['tips']}}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div><!-- main-body -->
        <div class="main-footer">
            <span class="footer-text mr-auto">
                {{trans('master.thanks',[config('zotop.name')])}}
            </span>
            <span class="footer-text">
                <span class="badge badge-primary px-2">v{{config('zotop.version')}}</span>
                <span class="badge badge-success px-2"><i class="fa fa-server"></i> {{config('app.env')}}</span>
                @if(config('app.debug'))
                    <span class="badge badge-warning px-2"> <i class="fa fa-bug"></i> debug</span>
                @endif
            </span>
        </div>
    </div>

@endsection
