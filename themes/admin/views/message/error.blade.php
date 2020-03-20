{{-- title:提示信息模板 --}}
@extends('layouts.master')

@section('content')
<div class="d-flex full-width full-height bg-primary text-white">
    <div class="container-fluid text-left align-self-center">
        <div class="msg">
            <div class="msg-title text-uppercase"><i class="fa fa-times-circle"></i> {{$type}}</div>
            <div class="msg-content">{{$content}}</div>
            <div class="msg-buttons">
                @if (isset($url))
                <a href="{{$url}}" class="btn btn-outline text-white">
                    <i class="fa fa-check mr-2"></i> {{trans('master.ok')}}
                </a>
                @endif
                <a href="{{request()->referer()}}" class="btn btn-outline text-white">
                    <i class="fa fa-arrow-left mr-2"></i> {{trans('master.page.previous')}}
                </a>                
            </div>
        </div>
    </div>
</div>
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
@endsection

@push('css')
<style type="text/css">
    .msg{width: 80%;padding: 0 8rem;}
    .msg-title{font-weight: 900;font-size: 1.5rem;}
    .msg-content{font-weight: 900;font-size: 3rem;margin:.5rem 0 1.5rem 0;}
</style>
@endpush

