@extends('layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}} : {{$name}}
        </div>
        <div class="main-action">
            {field type="submit" form="table-form" value="trans('master.save')" class="btn btn-primary"}
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
</div>
@endsection

@push('js')
<script type="text/javascript">
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
