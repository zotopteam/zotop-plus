@extends('core::layouts.master')

@section('content')
    
    <div class="full-width align-self-center text-center">
        {block slug="main"}
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col">
                {content type="list" slug="news-center" with="user" model="article" size="4"}
            </div>
        </div>
    </div>

@endsection

@push('css')

@endpush
