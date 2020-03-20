@extends('layouts.dialog')

@section('content')
    <div class="block-preview">
    @include($block->view)
    </div>
@endsection

@push('css')
<style type="text/css">
    .block-preview{margin:5rem;}
</style>
@endpush
