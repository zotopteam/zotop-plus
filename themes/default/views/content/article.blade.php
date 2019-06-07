{{--title:文章默认模板--}}
@extends('core::layouts.master')

@section('content')
<div class="container">
    {content:path id="$content->id"}

    <div class="content">
        <div class="content-header">
            <h1 class="content-title">{{$content->title}}</h1>
            <div class="content-info text-muted text-sm">
                <span>{{$content->created_at}}</span>
                <span>作者：{{$content->author ?? config('site.name')}}</span>
                <span>来源：{{$content->source ?? config('site.name')}}</span>
                <span>点击：{{$content->hits}}</span>
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