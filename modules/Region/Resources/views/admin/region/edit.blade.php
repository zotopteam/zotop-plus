@extends('layouts.dialog')

@section('content')
<div class="main scrollable">
    {form bind="$region" route="['region.update', $region['id']]" method="post" class="form m-5" autocomplete="off"}
    <div class="container-fluid">
        <div class="form-group">
            <label for="name" class="form-label">{{trans('region::region.parent')}}</label>
            <div class="form-field">
                <input type="hidden" class="form-control" name="parent_id" value="{{$region->parent_id}}">
                {field type="text" value="$parent_region_title" required="required" disabled="disabled"}
            </div>
        </div>

        <div class="form-group">
            <label for="title" class="form-label required">{{trans('region::region.name')}}</label>
            <div class="form-field">
                {field type="text" name="title" required="required"}

                @if ($errors->has('title'))
                <span class="form-help text-error">{{ $errors->first('title') }}</span>
                @else
                <span class="form-help"></span>
                @endif
            </div>
        </div>

    </div>
    {/form}
</div>
@endsection

@push('js')
<script type="text/javascript">
    // 对话框设置
    currentDialog.callbacks['ok'] = function(){
        $('form.form').submit();
        return false;
    };

</script>
@endpush
