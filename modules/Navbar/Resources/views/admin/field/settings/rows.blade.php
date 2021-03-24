<div class="form-group row">
    <label for="settings-rows" class="col-2 col-form-label">{{trans('navbar::field.rows.label')}}</label>
    <div class="col-4">
        <div class="input-group">
            <z-field type="number" name="settings[rows]" value="$field->settings->rows ?? $type->settings->rows ?? 3"
                     min="$type->settings->rows ?? 2"/>
            <div class="input-group-append"><span class="input-group-text">{{trans('navbar::field.rows.unit')}}</span>
            </div>
        </div>
    </div>
</div>
