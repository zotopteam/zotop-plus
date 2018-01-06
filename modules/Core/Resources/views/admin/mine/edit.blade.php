@extends('core::layouts.master')

@section('content')

@include('core::mine.side')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">
            {form model="$user" route="core.mine.update" method="put" id="form" autocomplete="off"}
                <div class="form-group row">
                    <label for="username" class="col-2 col-form-label required">{{trans('core::mine.username.label')}}</label>
                    <div class="col-4 col-md-6">
                        {field type="static" name="username"}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nickname" class="col-2 col-form-label required">{{trans('core::mine.nickname.label')}}</label>
                    <div class="col-4 col-md-6">
                        {field type="text" name="nickname" required="required" maxlength="100"}
                        
                        @if ($errors->has('nickname'))
                        <span class="form-help text-error">{{ $errors->first('nickname') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-2 col-form-label required">{{trans('core::mine.email.label')}}</label>
                    <div class="col-4 col-md-6">
                        {field type="email" name="email" required="required" placeholder="trans('core::mine.email.placeholder')" data-msg-required="trans('core::mine.email.required')"}
                        
                        @if ($errors->has('email'))
                        <span class="form-help text-error">{{ $errors->first('email') }}</span>
                        @endif                    
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mobile" class="col-2 col-form-label required">{{trans('core::mine.mobile.label')}}</label>
                    <div class="col-4 col-md-6">
                        {field type="mobile" name="mobile" required="required"}

                        @if ($errors->has('mobile'))
                        <span class="form-help text-error">{{ $errors->first('mobile') }}</span>
                        @endif                          
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sign" class="col-2 col-form-label">{{trans('core::mine.sign.label')}}</label>
                    <div class="col-8">
                        {field type="textarea" name="sign" rows="3" maxlength="255"}

                        @if ($errors->has('sign'))
                        <span class="form-help text-error">{{ $errors->first('sign') }}</span>
                        @endif                          
                    </div>
                </div>            

                <div class="form-group row">
                    <label for="login_times" class="col-2 col-form-label">{{trans('core::mine.login_times.label')}}</label>
                    <div class="col-4 col-md-6">
                        {field type="static" name="login_times"}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="login_ip" class="col-2 col-form-label">{{trans('core::mine.login_ip.label')}}</label>
                    <div class="col-4 col-md-6">
                        {field type="static" name="login_ip"}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="login_at" class="col-2 col-form-label">{{trans('core::mine.login_at.label')}}</label>
                    <div class="col-4 col-md-6">
                        {field type="static" name="login_at"}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="created_at" class="col-2 col-form-label">{{trans('core::mine.created_at.label')}}</label>
                    <div class="col-4 col-md-6">
                        {field type="static" name="created_at"}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="updated_at" class="col-2 col-form-label">{{trans('core::mine.updated_at.label')}}</label>
                    <div class="col-4 col-md-6">
                        {field type="static" name="updated_at"}
                    </div>
                </div>                                                  
            {/form}           
        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="form" value="trans('core::master.save')" class="btn btn-primary"}
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
