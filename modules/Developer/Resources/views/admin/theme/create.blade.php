@extends('layouts.dialog')

@section('content')
<div class="main scrollable">

    <z-form route="developer.theme.store" method="post" class="form m-4" autocomplete="off">

        <div class="container-fluid">

            <div class="form-group">
                <label for="name" class="form-label required">{{trans('developer::theme.name.label')}}</label>
                <div class="form-field">
                    <z-field type="text" name="name" pattern="^[a-z][A-Za-z]+$" required="required"/>

                    @if ($errors->has('name'))
                    <span class="form-help text-error">{{ $errors->first('name') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::theme.name.help')}}</span>                     
                    @endif
                </div>                      
            </div>

            <div class="form-group">
                <label for="plain" class="form-label required">{{trans('developer::theme.type.label')}}</label>
                <div class="form-field">
                    <z-field type="radiogroup" name="type" options="Module::data('developer::theme.types')" required="required" column="1"/>

                    @if ($errors->has('type'))
                    <span class="form-help text-error">{{ $errors->first('type') }}</span>
                    @else
                    <span class="form-help">{{trans('developer::theme.type.help')}}</span>                     
                    @endif
                </div>                     
            </div>                                             
                       
        </div>

    </z-form>
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
