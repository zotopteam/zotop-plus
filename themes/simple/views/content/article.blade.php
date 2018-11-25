@extends('core::layouts.master')

@section('content')
<div class="content">
    <h1 class="content-title">{{$content->title}}</h1>
    <div class="content-body">{!! $content->content !!}</div>
</div>
@endsection

@push('css')
@endpush
