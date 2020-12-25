@extends('layouts.master')

@section('content')
    @include('developer::form.side')

    <div class="main">
        <div class="main-header">
            <div class="main-title mr-auto">
                {{$title}}
            </div>
            <div class="main-action">

            </div>
        </div>
        <div class="main-body scrollable">

            @foreach($examples as $index => $include)
                <div class="card m-3">
                    <div class="card-header">
                        {{trans('developer::form.example')}} {{$index + 1}}
                    </div>
                    <div class="card-body">
                        @include($include, ['control' => $control, 'attribute' => $attribute])
                    </div>
                </div>
            @endforeach

            <div class="card m-3">
                <div class="card-header">
                    {{trans('developer::form.control.attributes')}}
                </div>

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td>{{trans('developer::form.control.attributes.key')}}</td>
                        <td>{{trans('developer::form.control.attributes.type')}}</td>
                        <td>{{trans('developer::form.control.attributes.required')}}</td>
                        <td>{{trans('developer::form.control.attributes.example')}}</td>
                        <td>{{trans('developer::form.control.attributes.text')}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($attributes as $key=>$attribute)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$attribute['type'] ?? ''}}</td>
                            <td class="text-center">
                                @if(Arr::get($attribute, 'required'))
                                    <i class="fa fa-check-circle text-success"></i>
                                @endif
                            </td>
                            <td>{{$attribute['example'] ?? ''}}</td>
                            <td>{{$attribute['text'] ?? ''}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

        </div> <!-- main-body -->
    </div>
@endsection
