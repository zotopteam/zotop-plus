@extends('layouts.master')

@section('content')


<div class="main">
    
    <div class="jumbotron bg-primary text-white text-center m-0 p-5">
        <div class="container-fluid">
            <h1>{{trans('translator::translator.title')}}</h1>
            <p>{{trans('translator::translator.description')}}</p>
        </div>
    </div>

    <div class="container-fluid scrollable">
            {form bind="$config" route="translator.config.index" method="post" id="config" autocomplete="off"}
            
            <div class="form-title row">{{trans('translator::config.base')}}</div>

            <div class="form-group row">
                <label for="engine" class="col-2 col-form-label required">{{trans('translator::config.engine')}}</label>
                <div class="col-8">
                    {field type="select" name="engine" options="Module::data('translator::engines')" required="required"}
                    
                    @if ($errors->has('engine'))
                    <span class="form-help text-error">{{ $errors->first('engine') }}</span>
                    @else
                    <span class="form-help">{{trans('translator::config.engine.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="from" class="col-2 col-form-label required">{{trans('translator::config.from_to')}}</label>
                <div class="col-8">
                    
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('translator::config.from')}}</span></div>
                        {field type="select" name="from" options="Module::data('translator::languages')" required="required"}
                        <div class="input-group-prepend"><span class="input-group-text">{{trans('translator::config.to')}}</span></div>
                        {field type="select" name="to" options="Module::data('translator::languages')" required="required"}
                    </div>

                    <span class="form-help">{{trans('translator::config.from_to.help')}}</span>

                </div>
            </div>
            
            <div class="engine d-none" data-depend="[name=engine]" data-when="value=baidu" data-then="show">
                <div class="form-title row">{{trans('translator::config.engines.baidu')}}</div>

                <div class="alert alert-warning my-3">
                    {!! trans('translator::config.baidu.help') !!}
                </div>

                <div class="form-group row">
                    <label for="baidu[appid]" class="col-2 col-form-label required">{{trans('translator::config.baidu.appid')}}</label>
                    <div class="col-8">
                        {field type="text" name="baidu[appid]" required="required"}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="baidu[secretkey]" class="col-2 col-form-label required">{{trans('translator::config.baidu.secretkey')}}</label>
                    <div class="col-8">
                        {field type="text" name="baidu[secretkey]" required="required"}
                    </div>
                </div>
            </div>

            <div class="engine d-none" class="engine" data-depend="[name=engine]" data-when="value=youdao" data-then="show">

                <div class="form-title row">{{trans('translator::config.engines.youdao')}}</div>

                <div class="alert alert-warning my-3">
                    {!! trans('translator::config.youdao.help') !!}
                </div>

                <div class="form-group row">
                    <label for="youdao[appid]" class="col-2 col-form-label required">{{trans('translator::config.youdao.appid')}}</label>
                    <div class="col-8">
                        {field type="text" name="youdao[appid]" required="required"}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="youdao[secretkey]" class="col-2 col-form-label required">{{trans('translator::config.youdao.secretkey')}}</label>
                    <div class="col-8">
                        {field type="text" name="youdao[secretkey]" required="required"}
                    </div>
                </div>
            </div>

            {/form}
    </div>
    <div class="main-footer">
        <div class="ml-auto">
            {field type="submit" form="config" value="trans('master.save')" class="btn btn-primary"}
        </div>
    </div>    
</div>
@endsection
