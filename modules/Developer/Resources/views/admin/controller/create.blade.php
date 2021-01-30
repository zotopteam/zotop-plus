@extends('layouts.dialog')

@section('content')
    <div class="main scrollable">

        <z-form route="['developer.controller.create', $module, $type]" method="post" class="form p-3"
                autocomplete="off">

            <div class="container-fluid">

                <div class="form-group">
                    <label for="name" class="form-label required">{{trans('developer::controller.name')}}</label>
                    <div class="form-field">
                        <z-field type="text" name="name" pattern="^[a-zA-z][a-zA-z0-9]+$" required="required"/>

                        @if ($errors->has('name'))
                            <span class="form-help text-error">{{ $errors->first('name') }}</span>
                        @else
                            <span class="form-help">{{trans('developer::controller.name.help')}}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="table" class="form-label">{{trans('developer::controller.table')}}</label>
                    <div class="form-field">
                        <z-field type="text" name="table"/>

                        @if ($errors->has('model'))
                            <span class="form-help text-error">{{ $errors->first('table') }}</span>
                        @else
                            <span class="form-help">{{trans('developer::controller.table.help')}}</span>
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
        currentDialog.callbacks['ok'] = function () {
            $('form.form').submit();
            return false;
        };

    </script>
@endpush
