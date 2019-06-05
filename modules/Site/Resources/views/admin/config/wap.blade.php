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
            {form model="$config" route="site.config.wap" method="post" id="config" autocomplete="off"}

            <div class="form-group row">
                <label for="wap-name" class="col-2 col-form-label">{{trans('site::config.wap.name.label')}}</label>
                <div class="col-8">
                    {field type="text" name="wap[name]"}
                    <span class="form-help">{{trans('site::config.wap.name.help')}}</span>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="wap-url" class="col-2 col-form-label">{{trans('site::config.wap.url.label')}}</label>
                <div class="col-8">
                    {field type="url" name="wap[url]"}
                    <span class="form-help">{{trans('site::config.wap.url.help')}}</span>
                </div>
            </div>

            <div class="form-group row">
                <label for="wap-theme" class="col-2 col-form-label required">{{trans('site::config.wap.theme.label')}}</label>
                <div class="col-8">
                    {field type="radiocards" name="wap[theme]" options="Module::data('site::theme.front')" class="radiocards-lg" column="4"}                
                    <span class="form-help">{{trans('site::config.wap.theme.help')}}</span>
                </div>
            </div>
            
            <div class="form-group row">
                <label for="wap-logo" class="col-2 col-form-label">{{trans('site::config.wap.logo.label')}}</label>
                <div class="col-8">
                    {field type="upload_image" name="wap[logo]" resize="false" watermark="false"}
                    <span class="form-help">{{trans('site::config.wap.logo.help')}}</span>
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
