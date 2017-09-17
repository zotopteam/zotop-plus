@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>          
        <div class="main-title mr-auto">
            {{$theme->title}} {{$title}}
        </div>
        <div class="main-action">            

        </div>           
    </div>
    <div class="main-body scrollable">
            @foreach($files as $file)

            @endforeach
    </div>
    <div class="main-footer">
        
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
