@extends('core::layouts.dialog')

@section('content')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
        </div>
        <div class="main-action">
            
        </div>           
    </div>
    <div class="main-body scrollable">
        @if($notifications->count() == 0)
            <div class="nodata">{{trans('master.nodata')}}</div>
        @else

        @endif
    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
