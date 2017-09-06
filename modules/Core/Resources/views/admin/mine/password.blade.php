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
            {form model="$user" route="core.mine.password.update" method="put" autocomplete="off"}
            <div class="form-group row">
                <label for="username" class="col-2 col-form-label required">{{trans('core::mine.username.label')}}</label>
                <div class="col-4">
                    {field type="static" name="username"}
                </div>
            </div>

            <div class="form-group row">
                <label for="password_old" class="col-2 col-form-label required">{{trans('core::mine.password_old.label')}}</label>
                <div class="col-4">
                    {field type="password" name="password_old" required="required"}
                    
                    @if ($errors->has('password_old'))
                    <span class="form-help text-error">{{ $errors->first('password_old') }}</span>
                    @else
                    <span class="form-help">{{trans('core::mine.password_old.help')}}</span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="password_new" class="col-2 col-form-label required">{{trans('core::mine.password_new.label')}}</label>
                <div class="col-4">
                    {field type="password" name="password_new" required="required" minlength="6"}

                    @if ($errors->has('password_new'))
                    <span class="form-help text-error">{{ $errors->first('password_new') }}</span>
                    @else
                    <span class="form-help">{{trans('core::mine.password_new.help')}}</span>                    
                    @endif                          
                </div>
            </div>

            <div class="form-group row">
                <label for="password_confirm" class="col-2 col-form-label required">{{trans('core::mine.password_confirm.label')}}</label>
                <div class="col-4">
                    {field type="password" name="password_confirm" required="required" equalto="#password_new"}

                    @if ($errors->has('password_confirm'))
                    <span class="form-help text-error">{{ $errors->first('password_confirm') }}</span>
                    @else
                    <span class="form-help">{{trans('core::mine.password_confirm.help')}}</span>                    
                    @endif                          
                </div>
            </div>
            <div class="form-group form-footer row">
                <div class="col-4">
                    {field type="submit" value="trans('core::master.save')" class="btn btn-primary"}
                </div>
            </div>                                          
            {/form}           
        </div>
    </div><!-- main-body -->    
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

                    if ( msg.state ) {
                        $(form).get(0).reset();
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
