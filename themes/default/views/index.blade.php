@extends('layouts.master')

@section('content')

<div class="container">
    <x-block slug="main" class="m-5" />
    <x-block slug="list" class="m-5" />
    <div class="row">
        <div class="col">
            {content:list slug="news-centres" subdir="true" size="5" cache="60"}
        </div>
    </div>
</div>

@endsection
