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

            @foreach($examples as $index => $attribute)
                <div class="card m-3">
                    <div class="card-header">
                        {{trans('developer::form.example')}}
                        @if(count($examples) > 1)
                            {{$index + 1}}
                        @endif
                    </div>
                    <div class="card-body">
                        @include('developer::form.control.common', ['control' => $control, 'attribute' => $attribute])
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
                            <td>
                                @if($value = Arr::get($attribute, 'value'))
                                    @foreach((array)$value as $val)
                                        <div class="text-primary">{{$val}}</div>
                                    @endforeach
                                @elseif($example = Arr::get($attribute, 'example'))
                                    @if(is_array($example))
                                        <pre>{{var_export_pretty($example, true)}}</pre>
                                    @else
                                        {{$example}}
                                    @endif
                                @endif
                            </td>
                            <td>{{$attribute['text'] ?? ''}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

        </div> <!-- main-body -->
    </div>
@endsection
