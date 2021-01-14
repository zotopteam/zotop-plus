@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{Request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <z-field type="submit" form="form-navbar" value="trans('master.save')" class="btn btn-primary"/>
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            <z-form bind="$navbar" route="['navbar.navbar.update', $navbar->id]" method="PUT" id="form-navbar" autocomplete="off">


            <div class="form-group row">
                <label for="title" class="col-2 col-form-label required">{{trans('navbar::navbar.title.label')}}</label>
                <div class="col-4">
                    <z-field type="text" name="title" required="required"/>

                    @if ($errors->has('title'))
                    <span class="form-help text-error">{{ $errors->first('title') }}</span>
                    @else
                    <span class="form-help">{{trans('navbar::navbar.title.help')}}</span>                     
                    @endif                       
                </div>
            </div>


            </z-form>

        </div>
    </div><!-- main-body -->
</div>
@endsection
