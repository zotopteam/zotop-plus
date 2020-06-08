{{-- title:搜索结果默认模板 --}}
@extends('layouts.master')

@section('content')
<div class="container">
    @if (! request('keywords'))
    <div class="py-5">
        {{trans('master.search.keywords.required')}}
    </div>

    @else
    @if ($list->count() > 0)
    <h4 class="py-5">{{trans('master.searching',[request('keywords')])}}</h4>
    <ul class="list-group list-group-flush">
        @foreach ($list as $item)
        <li class="list-group-item px-0">
            <a href="{{$item->url}}" target="_blank">{{$item->title}}</a>
            <span class="float-right">{{$item->created_at->format('Y-m-d')}}</span>
        </li>
        @endforeach
    </ul>

    <div class="mt-3">
        {{$list->withQueryString()->links()}}
    </div>
    @else
    <div class="py-5">
        {{trans('master.search.results.empty')}}
    </div>
    @endif
    @endif
</div>
@endsection
