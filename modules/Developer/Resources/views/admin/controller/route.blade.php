@extends('core::layouts.dialog')

@section('content')
<div class="main scrollable">

<pre class="p-5 m-0">
// {{$prefix}} group example
$router->group(['prefix' =>'{{$prefix}}'], function (Router $router) {
@foreach($router as $r)
@if($r['middleware'])
    $router->{{$r['verb']}}('{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}')->middleware('{{$r['middleware']}}');
@else
    $router->{{$r['verb']}}('{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}');
@endif
@endforeach
});

// {{$prefix}} example
@foreach($router as $r)
@if($r['middleware'])
$router->{{$r['verb']}}('{{$prefix}}/{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}')->middleware('{{$r['middleware']}}');
@else
$router->{{$r['verb']}}('{{$prefix}}/{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}');
@endif
@endforeach      
</pre>
</div>


@endsection
