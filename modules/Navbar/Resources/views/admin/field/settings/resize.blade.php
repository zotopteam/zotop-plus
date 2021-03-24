<div class="form-group row">
    <label for="settings-resize" class="col-2 col-form-label">{{trans('navbar::field.resize.label')}}</label>
    <div class="col-8">
        <z-field type="enable" name="settings[resize]"
                 value="$field->settings->resize ?? $type->settings->resize ?? 0"/>

        <span class="form-help">{{trans_find('navbar::field.resize.help')}}</span>
    </div>
</div>
