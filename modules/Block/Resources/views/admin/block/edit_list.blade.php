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

            {form model="$block" route="['block.update', $block->id]" id="block-form" method="put" autocomplete="off" referer="true"}

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
                <label for="slug" class="col-2 col-form-label required">{{trans('block::block.slug')}}</label>
                <div class="col-8">
                    {field type="translate" name="slug" source="name" format="slug" required="required" maxlength="64"}

                    @if ($errors->has('slug'))
                    <span class="form-help text-error">{{ $errors->first('slug') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.slug.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="rows" class="col-2 col-form-label required">{{trans('block::block.rows')}}</label>
                <div class="col-8">
                    <div class="input-group">
                            {field type="number" name="rows" min="0" required="required"}
                            <div class="input-group-append">
                                <span class="input-group-text">{{trans('block::block.rows.unit')}}</span>
                            </div>
                    </div>

                    @if ($errors->has('rows'))
                    <span class="form-help text-error">{{ $errors->first('rows') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.rows.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="form-group row">
                <label for="fields" class="col-2 col-form-label required">{{trans('block::block.fields')}}</label>
                <div class="col-9">

                    <div class="fields">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>    

                    @if ($errors->has('fields'))
                    <span class="form-help text-error">{{ $errors->first('fields') }}</span>
                    @else
                    <span class="form-help">{{trans('block::block.fields.help')}}</span>                     
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
            {field type="submit" form="block-form" value="trans('block::block.save')" class="btn btn-primary"}
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">

    // 加载字段
    $(function(){
        $('.fields').load('{{route('block.fields')}}', {
            'fields': {!! json_encode($block->fields) !!}
        }, function(){
            $(window).trigger('resize');
        });
    });

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
