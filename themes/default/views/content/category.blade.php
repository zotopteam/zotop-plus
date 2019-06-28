{{--title:分类默认模板--}}
@extends('core::layouts.master')

@section('content')
<div class="container">
    {content:path id="$content->id"}
    
    {content:list id="$content->top_id" subdir="false" self="true" current_id="$content->id" model="category" view="content::tag.nav"}

    <div class="content">
        <h1 class="content-title">{{$content->title}}</h1>
        <div class="content-body content-list">
            {content:list id="$content->id" subdir="true" paginate="true" size="15"}
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush
