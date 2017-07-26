@extends('layouts.master')

@section('content')
    <h1>Hello World</h1>
    {{$aaa}}/{{$bbb}} {{app('current.controller')}}
    <p>
        This view is loaded from module: {!! config('core.name') !!}
    </p>
@stop
