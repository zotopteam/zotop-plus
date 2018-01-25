@extends('core::layouts.master')

@section('content')

@include('core::config.side')

<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div> 
    
    <div class="main-body scrollable">
        <div class="container-fluid">
            {form model="config('mail')" route="core.config.mail" method="post" id="config" autocomplete="off"}

            <div class="form-title row">{{trans('core::config.mail.base')}}</div>
            <div class="form-group row">
                <label for="driver" class="col-2 col-form-label required">{{trans('core::config.mail.driver.label')}}</label>
                <div class="col-8">
                    {field type="select" options="Module::data('core::config.mail_drivers')" name="driver" required="required" column="1"}
                    
                    @if ($errors->has('driver'))
                    <span class="form-help text-error">{{ $errors->first('driver') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.mail.driver.help')}}</span>
                    <span class="form-help" data-depend="[name=driver]" data-when="value=log" data-then="show">
                    {{trans('core::config.mail.drivers.log.help')}}
                    </span>
                    @endif
                </div>
            </div>            
            <div class="form-group row">
                <label for="from[address]" class="col-2 col-form-label required">{{trans('core::config.mail.from.address.label')}}</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-envelope fa-fw"></i></span>
                        </div>
                        {field type="email" name="from[address]" required="required"}
                    </div>                   
                    
                    @if ($errors->has('from.address'))
                    <span class="form-help text-error">{{ $errors->first('from.address') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.mail.from.address.help')}}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="from[name]" class="col-2 col-form-label required">{{trans('core::config.mail.from.name.label')}}</label>
                <div class="col-8">
                    {field type="text" name="from[name]" required="required"}
                    
                    @if ($errors->has('from.name'))
                    <span class="form-help text-error">{{ $errors->first('from.name') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.mail.from.name.help')}}</span>
                    @endif
                </div>
            </div>  
            
            <div data-depend="[name=driver]" data-when="value=smtp" data-then="show">
                <div class="form-title row">{{trans('core::config.mail.smtp')}}</div>

                <div class="form-group row">
                    <label for="host" class="col-2 col-form-label">{{trans('core::config.mail.host.label')}}</label>
                    <div class="col-8">
                        {field type="text" name="host"}
                        
                        @if ($errors->has('host'))
                        <span class="form-help text-error">{{ $errors->first('host') }}</span>
                        @else
                        <span class="form-help">{{trans('core::config.mail.host.help')}}</span>
                        @endif
                    </div>
                </div>            
                <div class="form-group row">
                    <label for="port" class="col-2 col-form-label">{{trans('core::config.mail.port.label')}}</label>
                    <div class="col-8">
                        {field type="text" name="port"}
                        
                        @if ($errors->has('port'))
                        <span class="form-help text-error">{{ $errors->first('port') }}</span>
                        @else
                        <span class="form-help">{{trans('core::config.mail.port.help')}}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="encryption" class="col-2 col-form-label">{{trans('core::config.mail.encryption.label')}}</label>
                    <div class="col-8">
                        {field type="radiogroup" options="['tls'=>'TLS','ssl'=>'SSL']" name="encryption"}
                        
                        @if ($errors->has('encryption'))
                        <span class="form-help text-error">{{ $errors->first('encryption') }}</span>
                        @else
                        <span class="form-help">{{trans('core::config.mail.encryption.help')}}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="username" class="col-2 col-form-label">{{trans('core::config.mail.username.label')}}</label>
                    <div class="col-8">
                        {field type="text" name="username"}
                        
                        @if ($errors->has('username'))
                        <span class="form-help text-error">{{ $errors->first('username') }}</span>
                        @else
                        <span class="form-help">{{trans('core::config.mail.username.help')}}</span>
                        @endif
                    </div>
                </div>            
                <div class="form-group row">
                    <label for="password" class="col-2 col-form-label">{{trans('core::config.mail.password.label')}}</label>
                    <div class="col-8">
                        {field type="password2" name="password"}
                        
                        @if ($errors->has('password'))
                        <span class="form-help text-error">{{ $errors->first('password') }}</span>
                        @else
                        <span class="form-help">{{trans('core::config.mail.password.help')}}</span>
                        @endif
                    </div>
                </div>
            </div>
            {/form}
            {form route="core.config.mailtest" method="post" id="mailtest" autocomplete="off"}
            <div class="form-title row">{{trans('core::config.mail.test')}}</div>
            <div class="form-group row">
                <label for="test" class="col-2 col-form-label required">{{trans('core::config.mail.test.label')}}</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-envelope fa-fw"></i></span>
                        </div>
                        {field type="email" name="mailtest_sendto" required="required"}
                        <div class="input-group-append">
                            <button class="btn btn-primary form-submit" type="submit" id="mailtest">
                                <i class="fa fa-paper-plane fa-fw text-center"></i>
                                {{trans('core::config.mail.test.send')}}
                            </button>
                        </div>                        
                    </div>                   
                    
                    @if ($errors->has('from.address'))
                    <span class="form-help text-error">{{ $errors->first('from.address') }}</span>
                    @else
                    <span class="form-help">{{trans('core::config.mail.from.address.help')}}</span>
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
    // 表单提交
    $(function(){
        $('form#config').validate({       
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
    });


    $(function(){

        $('[name="from[address]"]').change(function(){
            var from = $(this).val();
            var user = from.split('@')[0];
            var host = from.split('@')[1];

            //控件赋值
            $('[name="host"]').val('smtp.' + host);
            $('[name="port"]').val(25);
            $('[name="username"]').val(user);
            $('[name="password"]').val('');
        });
    });


    // 表单提交
    $(function(){
        $('form#mailtest').validate({       
            submitHandler:function(form){                
                var validator = this;
                var data = $('form#config').serialize() +'&'+ $(form).serialize();

                $('.form-submit').prop('disabled',true);
                $.post($(form).attr('action'), data, function(msg){                    
                    $.msg(msg);
                    $('.form-submit').prop('disabled',false);
                    return false;
                },'json').fail(function(jqXHR){                    
                    $('.form-submit').prop('disabled',false);
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
    });
</script>
@endpush
