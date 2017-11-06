@extends('install.master')

@section('content')
        <section class="main d-flex scrollable">
            <div class="jumbotron bg-transparent full-width align-self-center text-center">           
                
                <h1>{{trans('installer.welcome',[config('app.name')])}}</h1>
                <p>{{trans('installer.welcome.description',[config('app.name')])}}</p>
                
                <div class="p-3">
                    <a href="{{config('app.homepage')}}" class="btn btn-outline text-white" target="_blank">
                        <i class="fa fa-globe fa-fw"></i> Homepage
                    </a>
                    &nbsp;
                    <a href="javascript:;" class="btn btn-outline text-white" target="_blank">
                        <i class="fa fa-circle-o fa-fw"></i> {{config('app.version')}}
                    </a>
                    &nbsp;
                    <a href="{{config('app.github')}}" class="btn btn-outline text-white" target="_blank">
                        <i class="fa fa-github fa-fw"></i> Github
                    </a>
                    &nbsp;
                    <a href="{{config('app.document')}}" class="btn btn-outline text-white" target="_blank">
                        <i class="fa fa-book fa-fw"></i> Document
                    </a>                
                    &nbsp;
                    <a href="{{config('app.help')}}" class="btn btn-outline text-white" target="_blank">
                        <i class="fa fa-question-circle fa-fw"></i> Help
                    </a>
                </div>
          
            </div>
        </section>    
@endsection

@section('wizard')

            <a href="{{route("install.$next")}}" class="btn btn-lg btn-success d-inline-block ml-auto">
                {{trans('installer.next')}} <i class="fa fa-angle-right fa-fw"></i> 
            </a>        

@endsection

@push('css')

@endpush

@push('js')

@endpush