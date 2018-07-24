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
            <a class="btn btn-secondary" href="{{route('block.datalist.history', $block->id)}}">
                <i class="fa fa-history"></i> {{trans('block::datalist.history')}}
            </a>            
            <a class="btn btn-primary" href="{{route('block.edit', $block->id)}}">
                <i class="fa fa-cog"></i> {{trans('block::block.setting')}}
            </a>            
        </div>
    </div>
    
    <div class="main-body scrollable">
            {form route="block.datalist.sort" action="post"}
            {field type="hidden" name="block_id" value="$block->id" required="required"}

            <table class="table table-nowrap table-sortable table-hover">
                <thead>
                <tr>
                    <td class="drag"></td>
                    <td width="2%">{{trans('block::datalist.row')}}</td>
                    <td>{{trans('block::datalist.title')}}</td>
                    <td width="10%">{{trans('core::master.lastmodify')}}</td>
                </tr>
                </thead>
                <tbody>
                    @foreach (\Modules\Block\Models\Datalist::publish($block->id) as $i=>$datalist)

                    <tr>
                        <td class="drag"> <input type="hidden" name="sort[]" value="{{$datalist->id}}"/> </td>
                        <td>{{$i+1}}</td>
                        <td valign="middle">
                            @if ($datalist->image_preview)
                                <a href="javascript:;" class="js-image" data-url="{{preview($datalist->image_preview)}}" data-title="{{$datalist->title}}">
                                    <div class="image-preview bg-image-preview text-center float-left mr-3">
                                        <img src="{{preview($datalist->image_preview, 64, 64)}}">
                                    </div>
                                </a>
                            @endif
                            <div class="title text-lg">
                                {{$datalist->title}}
                            </div>
                            <div class="manage">
                                @if ($datalist->stick)
                                <a class="manage-item js-confirm" href="{{route('block.datalist.stick', [$datalist->id, 0])}}">
                                    <i class="fas fa-arrow-circle-down"></i> {{trans('block::datalist.stick.off')}}
                                </a>
                                @else
                                <a class="manage-item js-confirm" href="{{route('block.datalist.stick', [$datalist->id, 1])}}">
                                    <i class="fas fa-arrow-circle-up"></i> {{trans('block::datalist.stick.on')}}
                                </a>
                                @endif                           
                                <a class="manage-item js-open" href="javascript:;"  data-url="{{route('block.datalist.edit', $datalist->id)}}" data-width="800" data-height="400">
                                    <i class="fa fa-edit"></i> {{trans('core::master.edit')}}
                                </a>
                                <a class="manage-item js-delete" href="javascript:;" data-url="{{route('block.datalist.destroy', $datalist->id)}}">
                                    <i class="fa fa-times"></i> {{trans('core::master.delete')}}
                                </a>
                            </div>
                        </td>
                        <td>
                            <b>{{$datalist->user->username}}</b>
                            <div class="text-sm">{{$datalist->updated_at}}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {/form}        

        @if ($block->data)


        @else
        <div class="nodata">{{trans('core::master.nodata')}}</div>
        @endif


    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {{$block->description}}
        </div>
        @if ($block->rows)        
        <div class="ml-auto text-nowrap">
            {{trans('block::block.rows')}} : {{$block->rows}}
        </div>
        @endif       
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
