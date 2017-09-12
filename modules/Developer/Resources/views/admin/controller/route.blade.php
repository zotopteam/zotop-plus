@extends('core::layouts.dialog')

@section('content')
<div class="main scrollable">

<pre class="p-5">
// {{$prefix}} group example
$router->group(['prefix' =>'/{{$prefix}}'], function (Router $router) {
@foreach($router as $r)
@if($r['middleware'])
    $router->{{$r['method']}}('/{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}')->middleware('{{$r['middleware']}}');
@else
    $router->{{$r['method']}}('/{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}');
@endif
@endforeach
});

// {{$prefix}} example
@foreach($router as $r)
@if($r['middleware'])
$router->{{$r['method']}}('/{{$prefix}}/{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}')->middleware('{{$r['middleware']}}');
@else
$router->{{$r['method']}}('/{{$prefix}}/{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}');
@endif
@endforeach      
</pre>
</div>


@endsection