@extends('core::layouts.master')

@section('content')
    <div class="block-preview">
        <ul class="list-group">
        @foreach ($data as $v)
            @if (isset($v['url']))
            <li class="list-group-item"><a href="{{$v['url'] or 'javascript:;'}}" target="_blank">{{$v['title']}}</a></li>
            @else
            <li class="list-group-item">{{$v['title']}}</li>
            @endif
        @endforeach
        </ul>
    </div>
@endsection

@push('css')
<style type="text/css">
    .block-preview{margin:5rem;}
</style>
@endpush
