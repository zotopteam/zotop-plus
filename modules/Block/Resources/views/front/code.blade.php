@extends('core::layouts.master')

@section('content')
    <div class="block-preview">
        {!! $data !!}
    </div>
@endsection

@push('css')
<style type="text/css">
    .block-preview{margin:5rem;}
</style>
@endpush
