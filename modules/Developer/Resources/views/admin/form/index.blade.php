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


        </div> <!-- main-body -->
    </div>
@endsection
