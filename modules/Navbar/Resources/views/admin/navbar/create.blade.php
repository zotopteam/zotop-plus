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

                <z-form bind="$navbar" route="navbar.navbar.store" method="post" id="form-navbar" autocomplete="off">

                    <div class="form-group row">
                        <label for="title" class="col-2 col-form-label ">{{trans('navbar::navbar.title.label')}}</label>
                        <div class="col-10">
                            <z-field type="text" name="title" maxlength="200">
                                @if ($errors->has('title'))
                                    <span class="form-help text-error">{{ $errors->first('title') }}</span>
                                @else
                                    <span class="form-help">{{trans('navbar::navbar.title.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="slug" class="col-2 col-form-label ">{{trans('navbar::navbar.slug.label')}}</label>
                        <div class="col-10">
                            <z-field type="slug" name="slug" maxlength="200">
                                @if ($errors->has('slug'))
                                    <span class="form-help text-error">{{ $errors->first('slug') }}</span>
                                @else
                                    <span class="form-help">{{trans('navbar::navbar.slug.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row d-none">
                        <label for="sort"
                               class="col-2 col-form-label required">{{trans('navbar::navbar.sort.label')}}</label>
                        <div class="col-10">
                            <z-field type="number" name="sort" required="required" min="0">
                                @if ($errors->has('sort'))
                                    <span class="form-help text-error">{{ $errors->first('sort') }}</span>
                                @else
                                    <span class="form-help">{{trans('navbar::navbar.sort.help')}}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="disabled"
                               class="col-2 col-form-label required">{{trans('navbar::navbar.disabled.label')}}</label>
                        <div class="col-10">
                            <z-field type="toggle" name="disabled" required="required">
                                @if ($errors->has('disabled'))
                                    <span class="form-help text-error">{{ $errors->first('disabled') }}</span>
                                @else
                                    <span class="form-help">{{trans('navbar::navbar.disabled.help')}}</span>
                            @endif
                        </div>
                    </div>


                </z-form>

            </div>
        </div><!-- main-body -->
    </div>
@endsection
