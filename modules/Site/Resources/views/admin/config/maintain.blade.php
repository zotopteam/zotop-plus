@extends('layouts.master')

@section('content')

@include('site::config.side')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <z-field type="submit" form="config" value="trans('master.save')" class="btn btn-primary"/>
        </div>
    </div> 
    
    <div class="main-body scrollable">
        <div class="container-fluid">
            <z-form bind="$config" route="site.config.maintain" method="post" id="config" autocomplete="off">
            
            <div class="form-group row">
                <label for="maintained" class="col-2 col-form-label">{{trans('site::config.maintained.label')}}</label>
                <div class="col-8">

                    <z-field type="toggle" name="maintained"/>
                    
                    @if ($errors->has('url'))
                    <div class="form-help text-error">{{ $errors->first('maintained') }}</div>
                    @else
                    <div class="form-help">{{trans('site::config.maintained.help')}}</div>
                    @endif
                </div>
            </div>  

            <div class="form-group row">
                <label for="maintaining" class="col-2 col-form-label">{{trans('site::config.maintaining.label')}}</label>
                <div class="col-8">

                    <z-field type="textarea" name="maintaining" rows="3"/>
                    
                    @if ($errors->has('maintaining'))
                    <div class="form-help text-error">{{ $errors->first('maintaining') }}</div>
                    @else
                    <div class="form-help">{{trans('site::config.maintaining.help')}}</div>
                    @endif
                </div>
            </div>
            </z-form>           
        </div>
    </div><!-- main-body -->
</div>

@endsection
