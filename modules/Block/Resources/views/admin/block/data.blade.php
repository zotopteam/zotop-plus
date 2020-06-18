@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        @if ($block->data)
        <div class="main-back">
            <a href="{{route('block.index',$block->category_id)}}">
                <i class="fa fa-angle-left"></i> <b>{{trans('master.back')}}</b>
            </a>
        </div>
        @endif
        <div class="main-title mr-auto">
            {{$block->name}}- {{$title}}
        </div>
        <div class="main-action">
            @if ($block->data)
            <a href="javascript:;" class="btn btn-info js-open" data-url="{{route('block.preview', $block->id)}}"
                data-width="80%" data-height="60%">
                <i class="fa fa-eye fa-fw"></i> {{trans('block::block.preview')}}
            </a>
            @endif
            <z-field type="submit" form="block-form" value="trans('block::block.save.edit')"
                class="btn btn-primary btn-save-edit" />
            <z-field type="submit" form="block-form" value="trans('block::block.save.back')"
                class="btn btn-success btn-save-back" />
            <a class="btn btn-info" href="{{route('block.edit', $block->id)}}">
                <i class="fa fa-cog fa-fw"></i> {{trans('block::block.setting')}}
            </a>
        </div>
    </div>

    <div class="main-body scrollable">

        <z-form bind="$block" route="['block.data', $id]" id="block-form" method="post" autocomplete="off"
            class="form m-2">

            @if ($block->type == 'code')
            <z-field type="code" name="data" height="500" required="required"
                placeholder="trans('block::block.data.placeholder.code')" />
            @elseif ($block->type == 'html')
            <z-field type="editor" name="data" height="500" required="required"
                placeholder="trans('block::block.data.placeholder.html')" source_id="$block->source_id"
                options="full" />
            @elseif ($block->type == 'text')
            <z-field type="textarea" name="data" rows="18" required="required"
                placeholder="trans('block::block.data.placeholder.text')" />
            @endif


            @if ($errors->has('data'))
            <span class="form-help text-error">{{ $errors->first('data') }}</span>
            @else
            <span class="form-help">{{$block->description}}</span>
            @endif

            <input type="hidden" name="operation">
        </z-form>

    </div><!-- main-body -->
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
    })
</script>
@endpush
