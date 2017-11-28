@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>     
    </div>
    <div class="main-body scrollable d-flex justify-content-center align-items-center">
    
                
                <div class="card text-center m-5">                    
                    <div class="card-icon bg-primary text-white d-flex justify-content-center align-items-center">
                        <i class="fa fa-puzzle-piece"></i>
                    </div>
                    <div class="card-body">
                        <a href="{{route('developer.module.index')}}"> {{trans('developer::module.title')}}</a>
                    </div>
                </div>

                <div class="card text-center m-5">                    
                    <div class="card-icon bg-success text-white d-flex justify-content-center align-items-center">
                        <i class="fa fa-diamond"></i>
                    </div>
                    <div class="card-body">
                        <a href="{{route('developer.theme.index')}}"> {{trans('developer::theme.title')}}</a>
                    </div>                    
                </div>             




    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            <i class="fa fa-circle-o fa-fw text-primary"></i> {{trans('developer::module.description')}}
        </div>
    </div>
</div>
@endsection

@push('css')
<style type="text/css">
.card-icon{width:18rem;height:12rem;font-size:4rem;}
</style>
@endpush
