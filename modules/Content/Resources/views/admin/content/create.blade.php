@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        <div class="main-title">
            {{$title}}
        </div>
        <div class="main-breadcrumb breadcrumb text-xs p-1 px-2 m-0 mx-2">
            <a class="breadcrumb-item" href="{{route('content.content.index')}}">{{trans('content::content.root')}}</a>
            @foreach($parents as $p)
            <a class="breadcrumb-item" href="{{route('content.content.index', $p->id)}}">{{$p->title}}</a> 
            @endforeach              
        </div>
        <div class="main-action ml-auto">
             {field type="submit" form="content-form" value="trans('content::content.status.draft')" class="btn btn-light" data-status="draft"}
             {field type="submit" form="content-form" value="trans('content::content.status.publish')" class="btn btn-success" data-status="publish" data-action="back"}
             {field type="submit" form="content-form" value="trans('content::content.status.future')" class="btn btn-primary d-none" data-status="future" data-action="back"}
        </div>
    </div>  
    <div class="main-body bg-light scrollable">
        <div class="container-fluid">

            {form model="$content" route="content.content.store" id="content-form" method="post" autocomplete="off"}
            
            {field type="hidden" name="parent_id" required="required"}
            {field type="hidden" name="model_id" required="required"}
            {field type="hidden" name="source_id" required="required"}
            {field type="hidden" name="status" required="required"}
            {field type="hidden" name="publish_at"}
            {field type="hidden" name="_action"}

            <div class="row">
                <div class="{{$form->side->count() ? 'col-9 col-md-9 col-sm-12' : 'col-12'}} d-flex flex-wrap p-0">
                    @foreach ($form->main as $item)
                        @include('content::content.field')
                    @endforeach                    
                </div>

                @if ($form->side->count())
                <div class="col-3 col-md-3 col-sm-12 p-0">
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
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){

        $('.form-submit').on('click', function(event) {
            event.preventDefault();
            var status = $(this).data('status');
            var action = $(this).data('action');
            if (status) {
                $('form.form').find('[name=status]').val(status);
            }
            if (action) {
                $('form.form').find('[name=_action]').val(action);
            }
            $('form.form').submit();
        })

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
