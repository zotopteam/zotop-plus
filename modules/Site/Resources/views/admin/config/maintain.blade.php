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
            {form model="$config" route="site.config.maintain" method="post" id="config" autocomplete="off"}
            
            <div class="form-group row">
                <label for="maintained" class="col-2 col-form-label">{{trans('site::config.maintained.label')}}</label>
                <div class="col-8">

                    {field type="toggle" name="maintained"}
                    
                    @if ($errors->has('url'))
                    <div class="form-help text-error">{{ $errors->first('maintained') }}</div>
                    @else
                    <div class="form-help">{{trans('site::config.maintained.help')}}</div>
                    @endif
                </div>
            </div>  

            <div class="form-group row">
                <label for="maintaining" class="col-2 col-form-label">{{trans('site::config.maintaining.label')}}</label>
                <div class="col-8">

                    {field type="textarea" name="maintaining" rows="3"}
                    
                    @if ($errors->has('maintaining'))
                    <div class="form-help text-error">{{ $errors->first('maintaining') }}</div>
                    @else
                    <div class="form-help">{{trans('site::config.maintaining.help')}}</div>
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
