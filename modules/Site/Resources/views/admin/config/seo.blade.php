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

                <z-form bind="$config" route="site.config.seo" method="post" id="config" autocomplete="off">
                    <div class="form-title row">{{trans('site::config.seo.global.title')}}</div>

                    <div class="form-group row">
                        <label for="title"
                               class="col-2 col-form-label required">{{trans('site::config.title.label')}}</label>
                        <div class="col-8">
                            <z-field type="text" name="title" required="required"/>

                            @if ($errors->has('title'))
                                <span class="form-help text-error">{{ $errors->first('title') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.title.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="keywords"
                               class="col-2 col-form-label required">{{trans('site::config.keywords.label')}}</label>
                        <div class="col-8">
                            <z-field type="text" name="keywords" required="required"/>

                            @if ($errors->has('keywords'))
                                <span class="form-help text-error">{{ $errors->first('keywords') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.keywords.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description"
                               class="col-2 col-form-label required">{{trans('site::config.description.label')}}</label>
                        <div class="col-8">
                            <z-field type="textarea" name="description" required="required" rows="5"/>

                            @if ($errors->has('description'))
                                <span class="form-help text-error">{{ $errors->first('description') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.description.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-title row">{{trans('site::config.seo.index.title')}}</div>

                    <div class="form-group row">
                        <label for="index_keywords"
                               class="col-2 col-form-label">{{trans('site::config.index_keywords.label')}}</label>
                        <div class="col-8">
                            <z-field type="text" name="index_keywords"/>

                            @if ($errors->has('index_keywords'))
                                <span class="form-help text-error">{{ $errors->first('index_keywords') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.index_keywords.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="index_meta"
                               class="col-2 col-form-label">{{trans('site::config.index_meta.label')}}</label>
                        <div class="col-8">

                            <z-field type="code" name="index_meta" height="200"/>

                            @if ($errors->has('index_meta'))
                                <span class="form-help text-error">{{ $errors->first('index_meta') }}</span>
                            @else
                                <span class="form-help">{{trans('site::config.index_meta.help')}}</span>
                            @endif
                        </div>
                    </div>
                </z-form>
            </div>
        </div><!-- main-body -->
    </div>

@endsection
