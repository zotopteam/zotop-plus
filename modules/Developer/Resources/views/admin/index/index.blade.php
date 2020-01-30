@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>     
    </div>
    <div class="main-body scrollable">
            
            <div class="grid grid-md p-4">
                @foreach (Module::data('developer::tools') as $tool)
                    <a href="{{$tool.href ?? 'javascript:;'}}" class="card text-center text-decoration-none {{$tool.class ?? 'bg-light'}}">                    
                        <div class="card-icon d-flex justify-content-center p-4">
                            <i class="{{$tool.icon ?? 'fa fa-hamburger'}} fa-6x align-self-center"></i>
                        </div>
                        <div class="card-body p-3 text-truncate">
                            {{$tool.text ?? ''}}
                        </div>
                    </a>
                @endforeach
          </div>

    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            <i class="fa fa-circle-o fa-fw text-primary"></i> {{trans('developer::developer.description')}}
        </div>
    </div>
</div>
@endsection

@push('css')
<style type="text/css">
   .card-icon{border-bottom:solid 1px rgba(0,0,0,0.05);}
</style>
@endpush
