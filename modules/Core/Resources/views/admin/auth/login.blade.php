@extends('core::layouts.login')

@section('content')
<section class="d-flex justify-content-center full-height">
    {form route="admin.login.post" method="post" class="form-login align-self-center" autocomplete="off"}
        <div class="card card-login">    
            <div class="card-header bg-primary text-white text-center pos-r">
                <div class="site-name text-overflow">{{config('site.name')}}</div>
                <svg class="animation-wave" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none">
                    <defs>
                        <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
                    </defs>
                    <g class="parallax">
                        <use xlink:href="#gentle-wave" x="50" y="0" fill="rgba(255,255,255,.5)"></use>
                        <use xlink:href="#gentle-wave" x="50" y="3" fill="rgba(255,255,255,.5)"></use>
                        <use xlink:href="#gentle-wave" x="50" y="6" fill="rgba(255,255,255,.5)"></use>
                    </g>
                </svg>              
            </div>
            <div class="card-body">        
                <div class="card-title form-tips">
                    {{trans('core::auth.tips')}} 
                </div>
                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <div class="input-group input-group-merge">
                            <label for="username" class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user fa-fw text-primary"></i></span>
                            </label>
                            {field type="text" name="username" required="required" autofocus="autofocus" placeholder="trans('core::auth.account.placeholder')" data-msg-required="trans('core::auth.account.required')"}
                            <div class="input-group-append" title="{{trans('core::auth.remember')}}" data-toggle="tooltip" data-placement="right">
                                <div class="input-group-text">
                                    <input type="checkbox" class="form-control text-muted text-md" name="remember" tabindex="-1" {{ old('remember') ? 'checked' : ''}}>
                                </div>
                            </div>
                        </div>

                        @if ($errors->has('username'))
                            <span class="form-help text-error">{{ $errors->first('username') }}</span>
                        @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <div class="input-group input-group-merge">
                            <label for="password" class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock fa-fw text-primary"></i></span>
                            </label>                    
                            {field type="password" name="password" required="required" placeholder="trans('core::auth.password.placeholder')" data-msg-required="trans('core::auth.password.required')"}
                        </div>

                        @if ($errors->has('password'))
                            <span class="form-help text-error">{{ $errors->first('password') }}</span>
                        @endif

                </div>
                <div class="form-group">
                        {field type="submit" value="trans('core::auth.submit')" class="btn btn-primary btn-block"}
                </div>
            </div>
        </div>
    {/form}
</section>
@endsection

@push('js')
<script type="text/javascript">

    $(function(){
        $('.form-login').draggable({containment:"html"});
    });

    $(function(){
        $('.form-login').validate({
            showErrors:function(errorMap,errorList){
                if (errorList[0]) $('.form-tips').html('<span class="text-error">'+ errorList[0].message +'</span>');
            },            
            submitHandler:function(form){                
                var validator = this;

                $('.form-tips').html('{{trans('core::auth.logining')}}');
                $('.form-submit').prop('disabled',true);

                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    
                    $('.form-tips').html(msg.content).addClass(msg.state ? 'text-success' : 'text-error');

                    if ( msg.state ) {
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
