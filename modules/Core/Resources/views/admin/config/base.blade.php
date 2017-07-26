@extends('core::layouts.master')

@section('content')

@include('core::config.site')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    {form model="config('cms.modules.core.site')" route="core.config.site.base" method="post" autocomplete="off"}
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            <div class="form-title row">{{trans('core::config.site.base.info.title')}}</div>

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('core::config.site.name.label')}}</label>
                <div class="col-8">
                    {field type="text" name="name" required="required"}
                    
                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.name.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="url" class="col-2 col-form-label required">{{trans('core::config.site.url.label')}}</label>
                <div class="col-8">

                    {field type="url" name="url"}
                    
                    @if ($errors->has('url'))
                    <span class="form-help text-error">{{ $errors->first('url') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.url.help')}}</span>
                    @endif
                </div>
            </div>  

            <div class="form-group row">
                <label for="theme" class="col-2 col-form-label required">{{trans('core::config.site.theme.label')}}</label>
                <div class="col-8">

                    <div class="row">
                    @foreach(Theme::getList('front') as $theme)
                        <div class="col-3">
                            <label class="check check-md">
                                {field type="radio" name="theme" value="$theme->name"}
                                <div class="card card-sm">
                                    <div class="image">
                                        <img class="card-img-top img-fluid" src="{{Theme::asset('img/placeholder.png')}}" style="background-image:url({{Theme::asset($theme->name.':theme.jpg')}});background-size:cover">
                                    </div>
                                    <div class="card-block p-2 text-center text-overflow">
                                        {{$theme->name}}                                        
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach                    
                    </div>                       
                    
                    @if ($errors->has('theme'))
                    <span class="form-help text-error">{{ $errors->first('theme') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.theme.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="logo" class="col-2 col-form-label">{{trans('core::config.site.logo.label')}}</label>
                <div class="col-8">

                    {field type="upload_image" name="logo"}
                    
                    @if ($errors->has('logo'))
                    <span class="form-help text-error">{{ $errors->first('logo') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.logo.help')}}</span>
                    @endif
                </div>
            </div>  

            <div class="form-group row">
                <label for="favicon" class="col-2 col-form-label">{{trans('core::config.site.favicon.label')}}</label>
                <div class="col-8">

                    {field type="upload_image" name="favicon"}
                    
                    @if ($errors->has('favicon'))
                    <span class="form-help text-error">{{ $errors->first('favicon') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.favicon.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-title row">{{trans('core::config.site.base.status.title')}}</div>

            <div class="form-group row">
                <label for="closed" class="col-2 col-form-label">{{trans('core::config.site.closed.label')}}</label>
                <div class="col-8">

                    {field type="bool" name="closed"}
                    
                    @if ($errors->has('url'))
                    <span class="form-help text-error">{{ $errors->first('closed') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.closed.help')}}</span>
                    @endif
                </div>
            </div>  

            <div class="form-group row">
                <label for="closed_reason" class="col-2 col-form-label">{{trans('core::config.site.closed_reason.label')}}</label>
                <div class="col-8">

                    {field type="textarea" name="closed_reason" rows="3"}
                    
                    @if ($errors->has('closed_reason'))
                    <span class="form-help text-error">{{ $errors->first('closed_reason') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.closed_reason.help')}}</span>
                    @endif
                </div>
            </div>
                       
        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" value="trans('core::master.save')" class="btn btn-primary"}
        </div>
    </div>
    {/form}
</div>

@endsection

@push('js')
<script type="text/javascript">
    $(function(){
        $('form.form').validate({       
            submitHandler:function(form){                
                var validator = this;

                $('.form-submit').prop('disabled',true);

                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    
                    $.msg(msg);

                    if ( msg.state && msg.url ) {
                        location.href = msg.url;
                        return true;
                    }

                    $('.form-submit').prop('disabled',false);
                    return false;

                },'json').fail(function(jqXHR){                    
                    $('.form-submit').prop('disabled',false);
                    return validator.showErrors(jqXHR.responseJSON);
                });
            }            
        });
    })
</script>
@endpush