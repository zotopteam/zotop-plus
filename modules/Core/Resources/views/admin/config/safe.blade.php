@extends('layouts.master')

@section('content')

@section('content')
    <x-sidebar data="core::config.navbar" :header="trans('core::config.title')"/>
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
                <z-form bind="$config" route="core.config.safe" method="post" id="config" autocomplete="off">

                    <div class="form-title row">{{trans('core::config.safe.base')}}</div>

                    <div class="form-group row">
                        <label for="env"
                               class="col-2 col-form-label required">{{trans('core::config.env.label')}}</label>
                        <div class="col-8">

                            <z-field type="radiocards" name="env"
                                     options="Module::data('core::config.envs')"
                                     column="1"
                                     required="required"
                            />

                            @if ($errors->has('env'))
                                <span class="form-help text-error">{{ $errors->first('env') }}</span>
                            @else
                                <span class="form-help">{{trans('core::config.env.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="debug" class="col-2 col-form-label">{{trans('core::config.debug.label')}}</label>
                        <div class="col-8">
                            <z-field type="toggle" name="debug"/>

                            @if ($errors->has('debug'))
                                <span class="form-help text-error">{{ $errors->first('debug') }}</span>
                            @else
                                <span class="form-help">{{trans('core::config.debug.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="log_sql"
                               class="col-2 col-form-label">{{trans('core::config.log_sql.label')}}</label>
                        <div class="col-8">
                            <z-field type="toggle" name="log_sql"/>

                            @if ($errors->has('log_sql'))
                                <span class="form-help text-error">{{ $errors->first('log_sql') }}</span>
                            @else
                                <span class="form-help">{{trans('core::config.log_sql.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-title row">{{trans('core::config.safe.admin')}}</div>

                    <div class="form-group row">
                        <label for="admin_prefix"
                               class="col-2 col-form-label required">{{trans('core::config.admin_prefix.label')}}</label>
                        <div class="col-8">
                            <z-field type="text" name="backend[prefix]" required="required"/>

                            @if ($errors->has('admin_prefix'))
                                <span class="form-help text-error">{{ $errors->first('admin_prefix') }}</span>
                            @else
                                <span class="form-help">{{trans('core::config.admin_prefix.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="debug" class="col-2 col-form-label">{{trans('core::config.log.label')}}</label>
                        <div class="col-8">

                            <z-field type="toggle" name="log[enabled]"/>

                            <div class="input-group" data-depend="[name='log[enabled]']" data-when="value=1"
                                 data-then="show">
                                <div class="input-group-prepend">
                            <span class="input-group-text">
                                {{trans('core::config.log.expire')}}
                            </span>
                                </div>
                                <z-field type="number" name="log[expire]" required="required" min="1"/>
                                <div class="input-group-append">
                            <span class="input-group-text">
                                {{trans('core::config.log.unit')}}
                            </span>
                                </div>
                            </div>

                            <span class="form-help">{{trans('core::config.log.help')}}</span>
                        </div>
                    </div>

                </z-form>
            </div>
        </div><!-- main-body -->
    </div>

@endsection
