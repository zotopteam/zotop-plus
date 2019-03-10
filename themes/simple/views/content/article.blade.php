@extends('core::layouts.master')

@section('content')
<div class="container">
    {content type="parents" id="$content->id"}

    <div class="content">
        <div class="content-header">
            <h1 class="content-title">{{$content->title}}</h1>
            <div class="content-info text-muted text-sm">
                <span>{{$content->created_at}}</span>
                <span>{{$content->author ?? config('site.name')}}</span>
                <span>{{$content->source ?? config('site.name')}}</span>
                <span>{{$content->hits}}</span>
            </div>
        </div>
        <div class="content-body">
            {!! $content->content !!}
        </div>
        <div class="content-footer">
            
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush
