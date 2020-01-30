@extends('layouts.master')

@section('content')
    
    <div class="full-width align-self-center text-center">
        {block slug="main"}
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col">
                {content:list slug="news-centres" subdir="true" size="5" cache="60"}
            </div>
        </div>
    </div>

@endsection

@push('css')

@endpush