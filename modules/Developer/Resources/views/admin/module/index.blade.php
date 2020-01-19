@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{route('developer.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>    
        <div class="main-title mx-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="javascript:;" data-url="{{route('developer.module.create')}}" data-width="800" data-height="300" class="btn btn-primary js-open"> <i class="fa fa-plus"></i> {{trans('developer::module.create')}}</a>
        </div>        
    </div>
    <div class="main-body scrollable">
        
        <div class="card-grid">
            @foreach ($modules as $module)
                <div class="card-grid-item text-center">
                    <a class="d-inline-block cur-p" href="{{route('developer.module.show',$module->name)}}">
                        <img src="{{preview($module->getPath('module.png'), 128, 128)}}">
                        <div class="py-2">
                            {{$module->getTitle()}}
                        </div>
                    </a>
                </div>
            @endforeach
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
    .card-grid{
        display: grid;
        grid-template-columns: repeat(auto-fill,minmax(10rem,1fr));
        grid-row-gap: 1.5rem;
        grid-column-gap: 1.5rem;
        padding: 1.5rem;
    }
    .card-grid-item{/*border: solid 1px #000*/}
</style>
@endpush
