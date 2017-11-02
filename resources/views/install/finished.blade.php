@extends('install.master')

@section('content')
    <section class="main d-flex scrollable">  
        <div class="jumbotron bg-transparent full-width align-self-center text-center">           
            <h1><i class="fa fa-check-circle fa-lg"></i></h1>
            <h1>{{trans("installer.$current",[config('app.name')])}}</h1>
            <p>{{trans("installer.$current.description",[config('app.name')])}}</p>
        </div>
    </section>
@endsection

@section('wizard')

            <a href="{{route('index')}}" class="btn btn-outline text-white d-inline-block mr-auto">
                <i class="fa fa-home fa-fw"></i> {{trans('installer.site.index')}}
            </a>
            
            <a href="{{route('admin.index')}}" class="btn btn-lg btn-success d-inline-block ml-auto">
                {{trans('installer.admin.index')}} <i class="fa fa-angle-right fa-fw"></i> 
            </a>

@endsection

@push('css')

@endpush

@push('js')

@endpush