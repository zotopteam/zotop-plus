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
            <z-form bind="$config" route="site.config.wap" method="post" id="config" autocomplete="off">

            <div class="form-group row">
                <label for="wap-name" class="col-2 col-form-label">{{trans('site::config.wap.name.label')}}</label>
                <div class="col-8">
                    <z-field type="text" name="wap[name]"/>
                    <span class="form-help">{{trans('site::config.wap.name.help')}}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="wap-url" class="col-2 col-form-label">{{trans('site::config.wap.url.label')}}</label>
                <div class="col-8">
                    <z-field type="url" name="wap[url]"/>
                    <span class="form-help">{{trans('site::config.wap.url.help')}}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="wap-theme" class="col-2 col-form-label required">{{trans('site::config.wap.theme.label')}}</label>
                <div class="col-8">
                    <z-field type="radiocards" name="wap[theme]" options="Module::data('site::theme.front')" class="radiocards-lg" column="4"/>
                    <span class="form-help">{{trans('site::config.wap.theme.help')}}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="wap-logo" class="col-2 col-form-label">{{trans('site::config.wap.logo.label')}}</label>
                <div class="col-8">
                    <z-field type="upload_image" name="wap[logo]" resize="false" watermark="false"/>
                    <span class="form-help">{{trans('site::config.wap.logo.help')}}</span>
                </div>
            </div>
            </z-form>
        </div>
    </div><!-- main-body -->
</div>

@endsection
