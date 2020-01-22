@extends('layouts.dialog')

@section('content')
<div class="main scrollable">

    {form route="['developer.controller.create', $module, $type]" method="post" class="p-3" autocomplete="off"}

        <div class="container-fluid">

            <div class="form-group">
                <label for="name" class="form-label required">{{trans('developer::controller.name')}}</label>
                <div class="form-field">
                    {field type="text" name="name" pattern="^[a-zA-z][a-zA-z0-9]+$" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::controller.name.help')}}</span>                     
                    @endif
                </div>                      
            </div>

            <div class="form-group">
                <label for="model" class="form-label">{{trans('developer::controller.model')}}</label>
                <div class="form-field">
                    {field type="text" name="model"}

                    @if ($errors->has('model'))
                    <span class="form-help text-error">{{ $errors->first('model') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::controller.model.help')}}</span>                     
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
