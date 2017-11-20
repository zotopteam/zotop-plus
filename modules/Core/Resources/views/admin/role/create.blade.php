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

            {form model="$role" route="core.role.store" method="post" id="role" autocomplete="off"}

            <div class="form-title row">{{trans('core::role.form.base')}}</div>

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('core::role.name.label')}}</label>
                <div class="col-8">
                    {field type="text" name="name" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('core::role.name.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-2 col-form-label required">{{trans('core::role.description.label')}}</label>
                <div class="col-8">
                    {field type="textarea" name="description" rows="3" maxlength="255"}

                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('core::role.description.help')}}</span>                    
                    @endif                          
                </div>
            </div>

            <div class="form-title row">{{trans('core::role.form.permission')}}</div>            


            {/form}

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="role" value="trans('core::master.save')" class="btn btn-primary"}
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
