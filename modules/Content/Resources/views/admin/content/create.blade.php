@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        <div class="main-title">
            {{$title}} : {{$parent->title}}
        </div>
        <div class="main-action ml-auto">
             {field type="submit" form="content-form" value="trans('core::master.save')" class="btn btn-primary"}
        </div>   
    </div>
    
    <div class="main-body bg-light scrollable">
        <div class="container-fluid">

            {form model="$content" route="content.content.store" id="content-form" method="post" autocomplete="off"}
            
            {field type="hidden" name="parent_id" required="required"}
            {field type="hidden" name="model_id" required="required"}
            {field type="hidden" name="status" required="required"}

            <div class="row">
                <div class="{{$form->side->count() ? 'col-9 col-md-9 col-sm-12' : 'col-12'}}">
                    @foreach ($form->main as $item)
                        @include('content::content.field')
                    @endforeach                    
                </div>

                @if ($form->side->count())
                <div class="col-3 col-md-3 col-sm-12">
                    @foreach ($form->side as $item)
                        @include('content::content.field')                       
                    @endforeach                    
                </div>
                @endif
            </div>
            {/form}

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
           
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
