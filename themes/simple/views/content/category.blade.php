@extends('core::layouts.master')

@section('content')
<div class="container">
    {content type="parents" id="$content->id"}
    
    {content id="$content->top_id" current_id="$content->id" model="category" view="content::tag.nav"}

    <div class="content">
        <h1 class="content-title">{{$content->title}}</h1>
        <div class="content-list">
            {content type="paginate" id="$content->id" model="article,link" child="category" with="user" size="5"}
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush
