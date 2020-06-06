@extends('layouts.master')

@section('content')
<x-sidebar data="core::config.navbar" :header="trans('core::config.title')" />
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            {field type="submit" form="config" value="trans('master.save')" class="btn btn-primary"}
        </div>
    </div>

    <div class="main-body scrollable">
        <div class="container-fluid">

            {form bind="config('app')" route="core.config.locale" method="post" id="config" autocomplete="off"}

            <div class="form-title row">{{trans('core::config.locale.timezone')}}</div>

            <div class="form-group row">
                <label for="locale" class="col-2 col-form-label required">{{trans('core::config.locale.label')}}</label>
                <div class="col-8">
                    {field type="select" name="locale" options="Module::data('core::config.languages')"}

                    @if ($errors->has('locale'))
                    <span class="form-help text-error">{{ $errors->first('locale') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.locale.help')}}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="timezone"
                    class="col-2 col-form-label required">{{trans('core::config.timezone.label')}}</label>
                <div class="col-8">
                    {field type="select" name="timezone" options="Module::data('core::config.timezones')"}

                    @if ($errors->has('timezone'))
                    <span class="form-help text-error">{{ $errors->first('timezone') }}</span>
                    @else
                    <span
                        class="form-help">{{trans('core::config.timezone.help',['utc'=>now('UTC'), 'locale'=>now()])}}</span>
                    @endif
                </div>
            </div>

            <div class="form-title row">{{trans('core::config.locale.datetime')}}</div>
            <div class="form-group row">
                <label for="date_format"
                    class="col-2 col-form-label required">{{trans('core::config.date_format.label')}}</label>
                <div class="col-8">
                    {field type="select" name="date_format" options="Module::data('core::config.date_formats')"}

                    @if ($errors->has('date_format'))
                    <span class="form-help text-error">{{ $errors->first('date_format') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.date_format.help')}}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="time_format"
                    class="col-2 col-form-label required">{{trans('core::config.time_format.label')}}</label>
                <div class="col-8">
                    {field type="select" name="time_format" options="Module::data('core::config.time_formats')"}

                    @if ($errors->has('time_format'))
                    <span class="form-help text-error">{{ $errors->first('time_format') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.time_format.help')}}</span>
                    @endif
                </div>
            </div>
            {/form}
        </div>
    </div><!-- main-body -->
</div>

@endsection
