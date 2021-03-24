<div class="form-group row">
    <label for="settings-placeholder" class="col-2 col-form-label">{{trans('navbar::field.placeholder.label')}}</label>
    <div class="col-8">
        <z-field type="text" name="settings[placeholder]"
                 value="$field->settings->placeholder ?? $type->settings->placeholder ?? null"
                 placeholder="placeholder"/>
        <div class="form-help">{{trans('navbar::field.placeholder.help')}}</div>
    </div>
</div>
