@extends('core::layouts.master')

@section('content')
    
    <div class="full-width align-self-center text-center">
        {block slug="test-block"}
        {block id="1" template="block::html"}
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col">
                {content slug="news-center" with="user" model="article" limit="4" template="content::block.list"}
            </div>
        </div>
    </div>

@endsection

@push('css')

@endpush
