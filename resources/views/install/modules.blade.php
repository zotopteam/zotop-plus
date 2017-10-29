@extends('install.master')

@section('content')

            <div class="jumbotron jumbotron-md bg-transparent full-width align-self-center text-center">           
                
                <h1>{{trans("installer.$current")}}</h1>
                <p>{{trans("installer.$current.description")}}</p>
            
          
            </div>
@endsection

@section('wizard')

            <a href="{{route("install.$prev")}}" class="btn btn-outline text-white prev d-inline-block mr-auto">
                <i class="fa fa-angle-left fa-fw"></i> {{trans('installer.prev')}}
            </a>
            
            @if($next)
            <a href="{{route("install.$next")}}" class="btn btn-lg btn-success d-inline-block ml-auto">
                {{trans('installer.next')}} <i class="fa fa-angle-right fa-fw"></i> 
            </a>
            @endif                       

@endsection

@push('css')

@endpush

@push('js')

@endpush