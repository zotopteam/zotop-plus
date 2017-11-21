@extends('core::layouts.master')

@section('content')

@include('site::config.side')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div> 
    
    <div class="main-body scrollable">
        <div class="container-fluid">
            {form model="$config" route="site.config.base" method="post" id="config" autocomplete="off"}
            <div class="form-title row">{{trans('site::config.base.info.title')}}</div>

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('site::config.name.label')}}</label>
                <div class="col-8">
                    {field type="text" name="name" required="required"}
                    
                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('site::config.name.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="url" class="col-2 col-form-label required">{{trans('site::config.url.label')}}</label>
                <div class="col-8">

                    {field type="url" name="url"}
                    
                    @if ($errors->has('url'))
                    <span class="form-help text-error">{{ $errors->first('url') }}</span>
                    @else
                    <span class="form-help">{{trans('site::config.url.help')}}</span>
                    @endif
                </div>
            </div>  

            <div class="form-group row">
                <label for="theme" class="col-2 col-form-label required">{{trans('site::config.theme.label')}}</label>
                <div class="col-8">
                    
                    {field type="radiocards" name="theme" options="Module::data('site::theme.front')" class="radiocards-lg"}                
                    
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

                    {field type="upload_image" name="logo"}
                    
                    @if ($errors->has('logo'))
                    <span class="form-help text-error">{{ $errors->first('logo') }}</span>
                    @else
                    <span class="form-help">{{trans('site::config.logo.help')}}</span>
                    @endif
                </div>
            </div>  

            <div class="form-group row">
                <label for="favicon" class="col-2 col-form-label">{{trans('site::config.favicon.label')}}</label>
                <div class="col-8">

                    {field type="upload_image" name="favicon"}
                    
                    @if ($errors->has('favicon'))
                    <span class="form-help text-error">{{ $errors->first('favicon') }}</span>
                    @else
                    <span class="form-help">{{trans('site::config.favicon.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-title row">{{trans('site::config.base.status.title')}}</div>

            <div class="form-group row">
                <label for="closed" class="col-2 col-form-label">{{trans('site::config.closed.label')}}</label>
                <div class="col-8">

                    {field type="enable" name="closed"}
                    
                    @if ($errors->has('url'))
                    <span class="form-help text-error">{{ $errors->first('closed') }}</span>
                    @else
                    <span class="form-help">{{trans('site::config.closed.help')}}</span>
                    @endif
                </div>
            </div>  

            <div class="form-group row">
                <label for="closed_reason" class="col-2 col-form-label">{{trans('site::config.closed_reason.label')}}</label>
                <div class="col-8">

                    {field type="textarea" name="closed_reason" rows="3"}
                    
                    @if ($errors->has('closed_reason'))
                    <span class="form-help text-error">{{ $errors->first('closed_reason') }}</span>
                    @else
                    <span class="form-help">{{trans('site::config.closed_reason.help')}}</span>
                    @endif
                </div>
            </div>
            {/form}           
        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="config" value="trans('core::master.save')" class="btn btn-primary"}
        </div>
    </div>
    
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
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
    })
</script>
@endpush
