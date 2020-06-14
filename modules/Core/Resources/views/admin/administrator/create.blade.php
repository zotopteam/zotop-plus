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
            <z-field type="submit" form="administrator" value="trans('master.save')" class="btn btn-primary"/>
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            <z-form bind="$user" route="core.administrator.store" method="post" id="administrator" autocomplete="off">

            <div class="form-title row">{{trans('core::administrator.form.base')}}</div>

            <div class="form-group row">
                <label for="username" class="col-2 col-form-label required">{{trans('core::administrator.username.label')}}</label>
                <div class="col-4">
                    <z-field type="text" name="username" required="required"/>

                    @if ($errors->has('username'))
                    <span class="form-help text-error">{{ $errors->first('username') }}</span>
                    @else
                    <span class="form-help">{{trans('core::administrator.username.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-2 col-form-label required">{{trans('core::administrator.password.label')}}</label>
                <div class="col-4">
                    <z-field type="password" name="password" required="required" minlength="6"/>

                    @if ($errors->has('password'))
                    <span class="form-help text-error">{{ $errors->first('password') }}</span>
                    @else
                    <span class="form-help">{{trans('core::administrator.password.help')}}</span>                    
                    @endif                          
                </div>
            </div>

            <div class="form-group row">
                <label for="password_confirm" class="col-2 col-form-label required">{{trans('core::administrator.password_confirm.label')}}</label>
                <div class="col-4">
                    <z-field type="password" name="password_confirm" required="required" equalto="#password"/>

                    @if ($errors->has('password_confirm'))
                    <span class="form-help text-error">{{ $errors->first('password_confirm') }}</span>
                    @else
                    <span class="form-help">{{trans('core::administrator.password_confirm.help')}}</span>                    
                    @endif                          
                </div>
            </div>
            
                      
            <div class="form-group row">
                <label for="model_id" class="col-2 col-form-label required">{{trans('core::administrator.roles.label')}}</label>
                <div class="col-6">

                    <z-field type="select" name="model_id" options="Module::data('core::administrator.models')" required="required"/>
                    
                    <div class="mt-2 d-none" data-depend="[name=model_id]" data-when="value=super" data-then="show">
                        <span class="form-help">{{ trans('core::administrator.model.super.description') }}</span>
                    </div>

                    <div class="mt-2 d-none" data-depend="[name=model_id]" data-when="value=admin" data-then="show">
                    <span class="form-help mb-3">{{ trans('core::administrator.model.admin.description') }}</span>
                    @if ($roles = Module::data('core::administrator.roles'))  
                    <z-field type="checkboxgroup" name="roles" options="$roles" required="required"/>
                    @else
                    <div class="form-control-plaintext">
                        <a href="{{route('core.role.index')}}">{{trans('core::role.required')}}</a>
                    </div>
                    @endif
                    </div>

                    @if ($errors->has('roles'))
                    <span class="form-help text-error">{{ $errors->first('roles') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-title row">{{trans('core::administrator.form.profile')}}</div>            

            <div class="form-group row">
                <label for="nickname" class="col-2 col-form-label required">{{trans('core::administrator.nickname.label')}}</label>
                <div class="col-4">
                    <z-field type="text" name="nickname" required="required"/>
                    
                    @if ($errors->has('nickname'))
                    <span class="form-help text-error">{{ $errors->first('nickname') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-2 col-form-label required">{{trans('core::administrator.mobile.label')}}</label>
                <div class="col-4">
                    <z-field type="mobile" name="mobile" required="required"/>

                    @if ($errors->has('mobile'))
                    <span class="form-help text-error">{{ $errors->first('mobile') }}</span>
                    @endif                          
                </div>
            </div>           

            <div class="form-group row">
                <label for="email" class="col-2 col-form-label required">{{trans('core::administrator.email.label')}}</label>
                <div class="col-4">
                    <z-field type="email" name="email" required="required" data-msg-required="trans('core::administrator.email.required')"/>
                    
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
                    <z-field type="textarea" name="sign" rows="3"/>

                    @if ($errors->has('sign'))
                    <span class="form-help text-error">{{ $errors->first('sign') }}</span>
                    @endif                          
                </div>
            </div>

            </z-form>

        </div>
    </div><!-- main-body -->
</div>
@endsection
