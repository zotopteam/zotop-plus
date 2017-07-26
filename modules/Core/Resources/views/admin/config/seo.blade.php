@extends('core::layouts.master')

@section('content')

@include('core::config.site')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    {form model="config('cms.modules.core.site')" route="core.config.site.seo" method="post" autocomplete="off"}
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            <div class="form-title row">{{trans('core::config.site.seo.global.title')}}</div>

            <div class="form-group row">
                <label for="title" class="col-2 col-form-label required">{{trans('core::config.site.title.label')}}</label>
                <div class="col-8">
                    {field type="text" name="title" required="required"}
                    
                    @if ($errors->has('title'))
                    <span class="form-help text-error">{{ $errors->first('title') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.title.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="keywords" class="col-2 col-form-label required">{{trans('core::config.site.keywords.label')}}</label>
                <div class="col-8">
                    {field type="text" name="keywords" required="required"}
                    
                    @if ($errors->has('keywords'))
                    <span class="form-help text-error">{{ $errors->first('keywords') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.keywords.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-2 col-form-label required">{{trans('core::config.site.description.label')}}</label>
                <div class="col-8">
                    {field type="textarea" name="description" required="required" rows="5"}
                    
                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.description.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-title row">{{trans('core::config.site.seo.index.title')}}</div>

            <div class="form-group row">
                <label for="index_keywords" class="col-2 col-form-label">{{trans('core::config.site.index_keywords.label')}}</label>
                <div class="col-8">
                    {field type="text" name="index_keywords"}
                    
                    @if ($errors->has('index_keywords'))
                    <span class="form-help text-error">{{ $errors->first('index_keywords') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.index_keywords.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="index_meta" class="col-2 col-form-label">{{trans('core::config.site.index_meta.label')}}</label>
                <div class="col-8">
                    {field type="textarea" name="index_meta" placeholder="trans('core::config.site.index_meta.placeholder')" rows="5"}
                    
                    @if ($errors->has('index_meta'))
                    <span class="form-help text-error">{{ $errors->first('index_meta') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.site.index_meta.help')}}</span>
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