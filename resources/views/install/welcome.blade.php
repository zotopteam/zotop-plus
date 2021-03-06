@extends('install.master')

@section('content')
        <section class="main d-flex scrollable">
            <div class="jumbotron bg-transparent full-width align-self-center text-center">           
                
                <h1>{{trans('installer.welcome',[config('zotop.title')])}}</h1>
                <p>{{config('zotop.description')}}</p>
                
                <div class="p-3">
                    <a href="{{config('zotop.homepage')}}" class="btn btn-outline text-white" target="_blank">
                        <i class="fas fa-globe fa-fw"></i> Homepage
                    </a>
                    &nbsp;
                    <a href="javascript:;" class="btn btn-outline text-white" target="_blank">
                        <i class="fas fa-code-branch fa-fw"></i> {{config('zotop.version')}}
                    </a>
                    &nbsp;
                    <a href="{{config('zotop.github')}}" class="btn btn-outline text-white" target="_blank">
                        <i class="fab fa-github fa-fw"></i> Github
                    </a>
                    &nbsp;
                    <a href="{{config('zotop.document')}}" class="btn btn-outline text-white" target="_blank">
                        <i class="fas fa-book fa-fw"></i> Document
                    </a>                
                    &nbsp;
                    <a href="{{config('zotop.help')}}" class="btn btn-outline text-white" target="_blank">
                        <i class="fas fa-question-circle fa-fw"></i> Help
                    </a>
                </div>
          
            </div>
        </section>    
@endsection

@section('wizard')
    <a href="{{route("install.{$next}")}}" class="btn btn-lg btn-success d-inline-block ml-auto">
        {{trans('installer.next')}} <i class="fa fa-angle-right fa-fw"></i> 
    </a>
@endsection
