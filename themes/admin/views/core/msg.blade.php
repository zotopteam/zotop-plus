{{-- title:提示信息模板 --}}
@extends('core::layouts.master')

@section('content')
<div class="d-flex full-width full-height">
    <div class="container-fluid text-center align-self-center">
        @if($msg.type == 'success')
        <i class="{{$msg.icon}} fa-5x mb-5 text-success"></i>
        <h1>{{$msg.content}}</h1>
        <div class="mt-5">
            <a href="{{$msg.url}}" class="btn btn-primary">
                <i class="fa fa-link fa-fw"></i> OK
            </a>
        </div>
        @elseif ($msg.type == 'error')
        <i class="{{$msg.icon}} fa-5x mb-5 text-error"></i>
        <h1>{{$msg.content}}</h1>
        @endif
    </div>
</div>
@endsection

