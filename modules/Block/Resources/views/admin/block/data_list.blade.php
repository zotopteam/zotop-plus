@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        @if ($block->data)        
        <div class="main-back">
            <a href="{{route('block.index',$block->category_id)}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        @endif
        <div class="main-title mr-auto">
            {{$block->name}} - {{$title}}
        </div>
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary js-open" data-url="{{route('block.datalist.create', $block->id)}}" data-width="800" data-height="400">
                <i class="fa fa-plus"></i> {{trans('core::master.create')}}
            </a>        
            <a class="btn btn-primary" href="{{route('block.edit', $block->id)}}">
                <i class="fa fa-cog"></i> {{trans('block::block.setting')}}
            </a>            
        </div>
    </div>
    
    <div class="main-body scrollable">

        @if ($block->data)

        @else
        <div class="nodata">{{trans('core::master.nodata')}}</div>
        @endif


    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {{$block->description}}
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){

        // 保存并继续编辑
        $('.btn-save-edit').on('click',function(){
            $('[name=operation]').val('save-edit');
            $('form.form').submit();
        });

        // 保存并返回列表
         $('.btn-save-back').on('click',function(){
            $('[name=operation]').val('save-back');
            $('form.form').submit();
        });       

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
