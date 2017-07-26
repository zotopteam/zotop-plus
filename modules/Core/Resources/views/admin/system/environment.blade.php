@extends('core::layouts.master')

@section('content')
<div class="main scrollable">
    
    <div class="jumbotron bg-primary text-white text-center">
        <div class="container-fluid">
            <h1>{{trans('core::system.environment.title')}}</h1>
            <p>{{trans('core::system.environment.description')}}</p>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card mb-3">
            <div class="card-header">
                {{trans('core::system.environment.server')}}
                <p>{{trans('core::system.environment.server')}}</p>
            </div>
            <div class="card-block">
                {{trans('core::system.environment.server')}}
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                {{trans('core::system.environment.files')}}
            </div>
            <div class="card-block">
                {{trans('core::system.environment.files')}}
            </div>
        </div>        
    </div>
@endsection