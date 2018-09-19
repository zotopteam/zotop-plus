@extends('core::layouts.dialog')

@section('content')
    <div class="block-preview">
    @include($template)
    </div>
@endsection

@push('css')
<style type="text/css">
    .block-preview{margin:5rem;}
</style>
@endpush
