@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">
            
            {form model="$user" route="['core.administrator.update', $id]" method="PUT" id="administrator" autocomplete="off"}

            <div class="form-title row">{{trans('core::administrator.form.base')}}</div>

            <div class="form-group row">
                <label for="username" class="col-2 col-form-label required">{{trans('core::administrator.username.label')}}</label>
                <div class="col-4">
                    {field type="text" name="username" required="required"}

                    @if ($errors->has('username'))
                    <span class="form-help text-error">{{ $errors->first('username') }}</span>
                    @else
                    <span class="form-help">{{trans('core::administrator.username.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="password_new" class="col-2 col-form-label">{{trans('core::administrator.password_new.label')}}</label>
                <div class="col-4">
                    {field type="password" name="password_new" minlength="6"}

                    @if ($errors->has('password_new'))
                    <span class="form-help text-error">{{ $errors->first('password_new') }}</span>
                    @else
                    <span class="form-help">{{trans('core::administrator.password_new.help')}}</span>                    
                    @endif                          
                </div>
            </div>

            <div class="form-group row">
                <label for="password_confirm" class="col-2 col-form-label">{{trans('core::administrator.password_confirm.label')}}</label>
                <div class="col-4">
                    {field type="password" name="password_confirm" equalto="#password_new"}

                    @if ($errors->has('password_confirm'))
                    <span class="form-help text-error">{{ $errors->first('password_confirm') }}</span>
                    @else
                    <span class="form-help">{{trans('core::administrator.password_confirm.help')}}</span>                    
                    @endif                          
                </div>
            </div>
            @if (! $user->isSuper())
            <div class="form-group row">
                <label for="nickname" class="col-2 col-form-label required">{{trans('core::administrator.roles.label')}}</label>
                <div class="col-4">
                    {field type="checkboxgroup" name="roles" value="Module::data('core::administrator.roles',[$user])" options="Module::data('core::administrator.roles')" required="required" minlength="1"}
                    
                    @if ($errors->has('roles'))
                    <span class="form-help text-error">{{ $errors->first('roles') }}</span>
                    @endif
                </div>
            </div>
            @endif
            
            <div class="form-title row">{{trans('core::administrator.form.profile')}}</div>            

            <div class="form-group row">
                <label for="nickname" class="col-2 col-form-label required">{{trans('core::administrator.nickname.label')}}</label>
                <div class="col-4">
                    {field type="text" name="nickname" required="required"}
                    
                    @if ($errors->has('nickname'))
                    <span class="form-help text-error">{{ $errors->first('nickname') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-2 col-form-label required">{{trans('core::administrator.mobile.label')}}</label>
                <div class="col-4">
                    {field type="mobile" name="mobile" required="required"}

                    @if ($errors->has('mobile'))
                    <span class="form-help text-error">{{ $errors->first('mobile') }}</span>
                    @endif                          
                </div>
            </div>           

            <div class="form-group row">
                <label for="email" class="col-2 col-form-label required">{{trans('core::administrator.email.label')}}</label>
                <div class="col-4">
                    {field type="email" name="email" required="required" data-msg-required="trans('core::administrator.email.required')"}
                    
                    @if ($errors->has('email'))
                    <span class="form-help text-error">{{ $errors->first('email') }}</span>
                    @else
                    <span class="form-help">{{trans('core::administrator.email.help')}}</span>                          
                    @endif                    
                </div>
            </div>

            <div class="form-group row">
                <label for="sign" class="col-2 col-form-label">{{trans('core::mine.sign.label')}}</label>
                <div class="col-6">
                    {field type="textarea" name="sign" rows="3"}

                    @if ($errors->has('sign'))
                    <span class="form-help text-error">{{ $errors->first('sign') }}</span>
                    @endif                          
                </div>
            </div>

            {/form} 

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="administrator" value="trans('core::master.save')" class="btn btn-primary"}
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
