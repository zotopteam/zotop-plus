<div class="form-group row">
    <label for="settings-unique" class="col-2 col-form-label">{{trans('navbar::field.unique.label')}}</label>
    <div class="col-8">
        <z-field type="bool" name="settings[unique]" value="$field->settings->unique ?? $type->settings->unique ?? 0"/>
        <span class="form-help">{{trans_find('navbar::field.unique.help')}}</span>
    </div>
</div>
