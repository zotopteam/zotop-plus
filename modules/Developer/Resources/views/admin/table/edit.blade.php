@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}} : {{$name}}
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            {form route="['developer.table.edit', $module, $name]" id="table-form" method="post" autocomplete="off"}

            <div class="form-group">
                <label for="name" class="form-label required">{{trans('developer::table.name')}}</label>
                <div class="form-field">
                    {field type="text" name="name" required="required" value="$name"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans_find('developer::table.name.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            <div class="columns">
                <i class="fa fa-spinner fa-spin"></i>
            </div>

            {/form}

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="ml-auto">
            {field type="submit" form="table-form" value="trans('master.save')" class="btn btn-primary"}
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
                }, 'json').fail(function(jqXHR){
                    $('.form-submit').prop('disabled',false);
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
    });

    // 加载字段
    $(function(){
        $('.columns').load('{{route('developer.table.columns')}}', {
            'columns': {!! json_encode($columns) !!},
            'indexes': {!! json_encode($indexes) !!}
        }, function(){
            $(window).trigger('resize');
        });
    });
</script>
@endpush
