@extends('core::layouts.dialog')

@section('content')
<div class="main scrollable">

    {form model="$module" route="developer.module.store" method="post" class="m-4" autocomplete="off"}

        <div class="container-fluid">

            <div class="form-group">
                <label for="name" class="form-label required">{{trans('developer::module.name.label')}}</label>
                <div class="form-field">
                    {field type="text" name="name" pattern="^[a-z]+$" required="required"}

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::module.name.help')}}</span>                     
                    @endif
                </div>                      
            </div>

            <div class="form-group">
                <label for="plain" class="form-label required">{{trans('developer::module.plain.label')}}</label>
                <div class="form-field">
                    {field type="radiogroup" name="plain" options="$plains" required="required" column="1"}

                    @if ($errors->has('plain'))
                    <span class="form-help text-error">{{ $errors->first('plain') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::module.plain.help')}}</span>                     
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
