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

            {form model="$model" route="['content.model.update', $id]" id="model-form" method="put" autocomplete="off"}

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('content::model.name.label')}}</label>
                <div class="col-8">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            {field type="icon" name="icon" required="required"}
                        </div>
                        {field type="text" name="name" required="required"}
                    </div>

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.name.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="id" class="col-2 col-form-label required">{{trans('content::model.id.label')}}</label>
                <div class="col-8">
                    
                    {field type="translate" name="id" source="name" format="id" required="required" maxlength="64"}

                    @if ($errors->has('id'))
                    <span class="form-help text-error">{{ $errors->first('id') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.id.help')}}</span>                     
                    @endif                       
                </div>
            </div>            

            <div class="form-group row">
                <label for="description" class="col-2 col-form-label required">{{trans('content::model.description.label')}}</label>
                <div class="col-8">
                    {field type="textarea" name="description" maxlength="255" rows="3"}

                    @if ($errors->has('description'))
                    <span class="form-help text-error">{{ $errors->first('description') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.description.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="template" class="col-2 col-form-label required">{{trans('content::model.template.label')}}</label>
                <div class="col-8">
                    {field type="template" name="template" required="required"}

                    @if ($errors->has('template'))
                    <span class="form-help text-error">{{ $errors->first('template') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.template.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="nestable" class="col-2 col-form-label required">{{trans('content::model.nestable.label')}}</label>
                <div class="col-8">
                    {field type="toggle" name="nestable" required="required"}

                    @if ($errors->has('nestable'))
                    <span class="form-help text-error">{{ $errors->first('nestable') }}</span>
                    @else
                    <span class="form-help">{{trans('content::model.nestable.help')}}</span>                     
                    @endif                       
                </div>
            </div>            

            {/form}

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="model-form" value="trans('core::master.save')" class="btn btn-primary"}
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
