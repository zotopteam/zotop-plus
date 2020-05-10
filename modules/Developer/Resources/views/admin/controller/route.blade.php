@extends('layouts.dialog')

@section('content')
<div class="main scrollable">

<pre class="p-5 m-0 text-primary">

@if($type == 'backend')
// {{$prefix}}
$router->group(['prefix' =>'{{$prefix}}'], function (Router $router) {
@foreach($router as $r)
    $router->{{$r['verb']}}('{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}')->middleware('allow:{{$r['module']}}.{{$r['controller']}}');
@endforeach
});

// {{$prefix}} 
@foreach($router as $r)
$router->{{$r['verb']}}('{{$prefix}}/{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}')->middleware('allow:{{$r['module']}}.{{$r['controller']}}');
@endforeach

// {{$prefix}}
$router->group(['prefix' =>'{{$prefix}}'], function (Router $router) {
@foreach($router as $r)
    $router->{{$r['verb']}}('{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}')->middleware('allow:{{$r['module']}}.{{$r['controller']}}.{{$r['method']}}');
@endforeach
});

// {{$prefix}} 
@foreach($router as $r)
$router->{{$r['verb']}}('{{$prefix}}/{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}')->middleware('allow:{{$r['module']}}.{{$r['controller']}}.{{$r['method']}}');
@endforeach
@else

// {{$prefix}}
$router->group(['prefix' =>'{{$prefix}}'], function (Router $router) {
@foreach($router as $r)
    $router->{{$r['verb']}}('{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}');
@endforeach
});

// {{$prefix}} 
@foreach($router as $r)
$router->{{$r['verb']}}('{{$prefix}}/{{$r['uri']}}','{{$r['action']}}')->name('{{$r['name']}}');
@endforeach
@endif

</pre>
</div>


@endsection
