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

            {form model="$field" route="content.field.store" id="field-form" method="post" autocomplete="off"}

             {field type="hidden" name="model_id" required="required"}

            <div class="form-title row">{{trans('content::field.form.base')}}</div>

            <div class="form-group row">
                <label for="label" class="col-2 col-form-label required">{{trans('content::field.label.label')}}</label>
                <div class="col-8">
                    {field type="text" name="label" required="required"}

                    @if ($errors->has('label'))
                    <span class="form-help text-error">{{ $errors->first('label') }}</span>
                    @else
                    <span class="form-help">{{trans('content::field.label.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="name" class="col-2 col-form-label required">{{trans('content::field.name.label')}}</label>
                <div class="col-8">
                    @if ($field->system)
                        {field type="translate" name="name" source="label" format="id" disabled="disabled"}
                    @else
                        {field type="translate" name="name" source="label" format="id" required="required" maxlength="64"}
                    @endif

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('content::field.name.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="help" class="col-2 col-form-label">{{trans('content::field.help.label')}}</label>
                <div class="col-8">
                    {field type="text" name="help"}

                    @if ($errors->has('help'))
                    <span class="form-help text-error">{{ $errors->first('help') }}</span>
                    @else
                    <span class="form-help">{{trans('content::field.help.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="type" class="col-2 col-form-label">{{trans('content::field.type.label')}}</label>
                <div class="col-8">

                    {field type="select" name="type" options="Module::data('content::field.type.options')" required="required"}

                    @if ($errors->has('type'))
                    <span class="form-help text-error">{{ $errors->first('type') }}</span>
                    @else
                    <span class="form-help">{{trans('content::field.type.help')}}</span>                     
                    @endif                       
                </div>
            </div>            

            <div id="field-settings">
                <i class="fa fa-spinner fa-spin d-none"></i>
            </div>            

            <div class="form-group row">
                <label for="default" class="col-2 col-form-label">{{trans('content::field.default.label')}}</label>
                <div class="col-8">
                    {field type="textarea" name="default" rows="2"}

                    @if ($errors->has('default'))
                    <span class="form-help text-error">{{ $errors->first('default') }}</span>
                    @else
                    <span class="form-help">{{trans('content::field.default.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-title row">{{trans('content::field.form.other')}}</div>

            <div class="form-group row">
                <label for="post" class="col-2 col-form-label">{{trans('content::field.post.label')}}</label>
                <div class="col-8">
                    {field type="bool" name="post"}

                    @if ($errors->has('post'))
                    <span class="form-help text-error">{{ $errors->first('post') }}</span>
                    @else
                    <span class="form-help">{{trans('content::field.post.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="col" class="col-2 col-form-label">{{trans('content::field.col.label')}}</label>
                <div class="col-8">
                    {field type="bool" name="col"}

                    @if ($errors->has('col'))
                    <span class="form-help text-error">{{ $errors->first('col') }}</span>
                    @else
                    <span class="form-help">{{trans('content::field.col.help')}}</span>                     
                    @endif                       
                </div>
            </div>  

            {/form}

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="field-form" value="trans('core::master.save')" class="btn btn-primary"}
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    function show_settings(type) {
        var field = @json($field);
            field.type = type;

        $.post("{{route('content.field.settings', $model->id)}}", {field:field}, function(html){
            $('#field-settings').html(html);
            $(window).trigger('resize');
        });
    }

    $(function(){
        show_settings('{{$field->type}}');

        $('[name=type]').on('change', function(){
            show_settings($(this).val());
        });
    });
</script>
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
