@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">        
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
        </div>           
    </div>
    <div class="main-body scrollable">
        <table class="table table-nowrap table-hover">
            <thead>
                <tr>
                    <td>#</td>
                    <td>{{trans('developer::route.uri')}} / {{trans('developer::route.action')}}</td>
                    <td>{{trans('developer::route.module')}}</td>
                    <td></td>
                    <td>{{trans('developer::route.domain')}}</td>
                    <td>{{trans('developer::route.name')}}</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($routes as $i=>$route)
                    <tr>
                        <td width="1%">{{$i}}</td>
                        <td>
                            <div class="title">{{$route->uri()}}</div>
                            <div class="description text-info">
                                {{$route->getActionName()}}
                            </div>
                        </td>
                        <td>{{array_get($route->getAction(), 'module')}}</td>
                        <td>{{array_get($route->getAction(), 'type')}}</td>
                        <td>{{$route->domain()}}</td>
                        <td>
                            {{$route->getName()}}
                        </td>                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="main-footer">

    </div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
