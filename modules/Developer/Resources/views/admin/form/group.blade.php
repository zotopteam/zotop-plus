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
            <div class="container-fluid">
                @foreach($controls as $control=>$attributes)
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{$control}}</label>
                        <div class="col-sm-10">
                            @include($include, ['control' => $control, 'attributes' => $attributes])
                        </div>
                    </div>
                @endforeach
            </div>
        </div> <!-- main-body -->
    </div>
@endsection
