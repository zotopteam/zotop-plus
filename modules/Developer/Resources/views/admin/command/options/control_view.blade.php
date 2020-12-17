<div class="form-group">
    <label for="--view" class="form-label">{{trans('developer::command.control.view.label')}}</label>
    <div class="form-field">
        <z-field type="checkboxgroup" name="--view" options="Module::data('developer::control.views')"
                 value="['backend','frontend']" column="1"/>

        @if ($errors->has('--view'))
            <span class="form-help text-error">{{ $errors->first('--view') }}</span>
        @else
            <span class="form-help">{{trans('developer::command.control.view.help')}}</span>
        @endif
    </div>
</div>
