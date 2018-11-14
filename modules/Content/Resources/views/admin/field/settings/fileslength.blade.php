    <div class="form-group row">
        <label for="settings-min" class="col-2 col-form-label">{{trans('content::field.fileslength.label')}}</label>
        <div class="col-4">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">{{trans('content::field.fileslength.min')}}</span></div>
                {field type="number" name="settings[min]" value="$field->settings->min ?? $type->settings->min ?? ''" min="$type->settings->min ?? null"}
                <div class="input-group-prepend"><span class="input-group-text">{{trans('content::field.fileslength.max')}}</span></div>
                {field type="number" name="settings[max]" value="$field->settings->max ?? $type->settings->max ?? ''" min="$type->settings->min ?? null" max="$type->settings->max ?? null"}
                <div class="input-group-append"><span class="input-group-text">{{trans('content::field.fileslength.unit')}}</span></div>

            </div>                     
        </div>
    </div>  
