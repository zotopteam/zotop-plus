@extends('layouts.master')

@section('content')

<div class="main">
    <div class="main-header d-none">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    <div class="main-body scrollable">
        <div class="grid grid-lg p-3">
                @foreach(filter::fire('global.start',[]) as $s)
                <a href="{{$s['href']}}" class="shortcut shortcut-media {{$s['class'] ?? ''}}" target="{{$s['target'] ?? '_self'}}">
                    <div class="shortcut-icon">
                        <i class="{{$s['icon']}}"></i>
                        @if(isset($s['badge']))
                        <b class="shortcut-badge badge badge-xs badge-danger">{{$s['badge']}}</b>
                        @endif       
                    </div>
                    <div class="shortcut-text">
                        <h2>{{$s['text']}}</h2>
                        <p>{{$s['tips']}}</p>      
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


