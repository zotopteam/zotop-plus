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
                <z-form bind="$config" route="site.config.base" method="post" id="config" autocomplete="off">
                    <div class="form-title row">{{trans('site::config.base.info')}}</div>

                    <div class="form-group row">
                        <label for="name"
                               class="col-2 col-form-label required">{{trans('site::config.name.label')}}</label>
                        <div class="col-8">
                            <z-field type="text" name="name" required="required"/>

                            @if ($errors->has('name'))
                                <span class="form-help text-error">{{ $errors->first('name') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.name.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="url"
                               class="col-2 col-form-label required">{{trans('site::config.url.label')}}</label>
                        <div class="col-8">

                            <z-field type="url" name="url"/>

                            @if ($errors->has('url'))
                                <span class="form-help text-error">{{ $errors->first('url') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.url.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="slogan" class="col-2 col-form-label">{{trans('site::config.slogan.label')}}</label>
                        <div class="col-8">
                            <z-field type="text" name="slogan"/>

                            @if ($errors->has('slogan'))
                                <span class="form-help text-error">{{ $errors->first('slogan') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.slogan.help')}}</span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="copyright"
                               class="col-2 col-form-label">{{trans('site::config.copyright.label')}}</label>
                        <div class="col-8">
                            <z-field type="text" name="copyright"/>

                            @if ($errors->has('copyright'))
                                <span class="form-help text-error">{{ $errors->first('copyright') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.copyright.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-title row">{{trans('site::config.base.theme')}}</div>

                    <div class="form-group row">
                        <label for="theme"
                               class="col-2 col-form-label required">{{trans('site::config.theme.label')}}</label>
                        <div class="col-8">

                            <z-field type="radiocards" name="theme" options="Module::data('site::theme.front')"
                                     class="radiocards-lg" column="4"/>

                            @if ($errors->has('theme'))
                                <span class="form-help text-error">{{ $errors->first('theme') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.theme.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="logo" class="col-2 col-form-label">{{trans('site::config.logo.label')}}</label>
                        <div class="col-8">

                            <z-field type="upload-image" name="logo" resize="false" watermark="false"/>

                            @if ($errors->has('logo'))
                                <span class="form-help text-error">{{ $errors->first('logo') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.logo.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="favicon"
                               class="col-2 col-form-label">{{trans('site::config.favicon.label')}}</label>
                        <div class="col-8">

                            <z-field type="upload" name="favicon" allow="png,ico" preview="image"/>

                            @if ($errors->has('favicon'))
                                <span class="form-help text-error">{{ $errors->first('favicon') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.favicon.help')}}</span>
                            @endif
                        </div>
                    </div>

                </z-form>
            </div>
        </div><!-- main-body -->
    </div>

@endsection
