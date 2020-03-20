{{--title:页面默认模板--}}
@extends('layouts.master')

@section('content')
<div class="container">
    {content:path id="$content->id"}

    <div class="content">
        <h1 class="content-title">{{$content->title}}</h1>
        <div class="content-body">{!! $content->content !!}</div>
    </div>
</div>
@endsection

@push('css')
@endpush
