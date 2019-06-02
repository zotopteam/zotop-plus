@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            
        </div>           
    </div>
    <div class="main-body scrollable">
        @if($notifications->count() == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else

        @endif
    </div>
    <div class="main-footer">
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
