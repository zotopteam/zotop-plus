@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        <div class="main-title mx-auto">
            {{$title}}
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            {form model="$block" route="block.store" id="block-form" method="post" autocomplete="off"}
            
            {field type="hidden" name="type" required="required"}

            <div class="form-group row">
                <label for="category_id" class="col-2 col-form-label required">{{trans('block::block.category_id')}}</label>
                <div class="col-8">
                    {field type="select" name="category_id" options="Module::data('block::category.select')" required="required"}

                    @if ($errors->has('category_id'))
                    <span class="form-help text-error">{{ $errors->first('category_id') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.category_id.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('block::block.name')}}</label>
                <div class="col-8">
                    {field type="text" name="name" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.name.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="code" class="col-2 col-form-label required">{{trans('block::block.code')}}</label>
                <div class="col-8">
                    {field type="translate" name="code" source="name" format="id" required="required" maxlength="64"}

                    @if ($errors->has('code'))
                    <span class="form-help text-error">{{ $errors->first('code') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.code.help')}}</span>                     
                    @endif                       
                </div>
            </div>            

            <div class="form-group row">
                <label for="description" class="col-2 col-form-label">{{trans('block::block.description')}}</label>
                <div class="col-8">
                    {field type="textarea" name="description" rows="3"}

                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.description.help')}}</span>                     
                    @endif                       
                </div>
            </div>            

            <div class="form-group row">
                <label for="template" class="col-2 col-form-label required">{{trans('block::block.template')}}</label>
                <div class="col-8">
                    {field type="template" name="template" required="required"}

                    @if ($errors->has('template'))
                    <span class="form-help text-error">{{ $errors->first('template') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.template.help')}}</span>                     
                    @endif                       
                </div>
            </div>            
            {/form}

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="ml-auto">
            {field type="submit" form="block-form" value="trans('block::block.save.next')" class="btn btn-primary"}
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
