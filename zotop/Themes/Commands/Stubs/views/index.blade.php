@extends('layouts.master')

@section('content')
<div class="container-fluid vh-100 d-flex justify-content-center">
    <div class="jumbotron bg-transparent m-0 text-center align-self-center">
        <h1 class="display-4">{{config('site.title') ?: config('zotop.title')}}</h1>
        <p class="lead">{{config('site.description') ?: config('zotop.description')}}</p>
        <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
    </div>
</div>
@endsection
