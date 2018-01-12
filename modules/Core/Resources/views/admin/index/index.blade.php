@extends('core::layouts.master')

@section('content')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    <div class="main-body scrollable">
        <div class="container-fluid">
            <div class="row">
                @foreach(filter::fire('global.start',[]) as $s)
                <div class="col-sm-6 col-md-4 col-lg-3">
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
                </div>
                @endforeach
            </div>
        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <span class="footer-text mr-auto">
            {{trans('core::master.thanks',[config('app.name')])}}
        </span>

        <span class="footer-text">
            v{{config('app.version')}}
        </span>        
    </div>
</div>

@endsection


