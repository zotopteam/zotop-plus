    <div class="form-group row">
        <label for="settings-minlength" class="col-2 col-form-label">{{trans('content::field.ranglength.label')}}</label>
        <div class="col-4">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">{{trans('content::field.ranglength.min')}}</span></div>
                <z-field type="number" name="settings[minlength]" value="$field->settings->minlength ?? $type->settings->minlength ?? 0" min="$type->settings->minlength ?? 0"/>
                <div class="input-group-prepend"><span class="input-group-text">{{trans('content::field.ranglength.max')}}</span></div>
                <z-field type="number" name="settings[maxlength]" value="$field->settings->maxlength ?? $type->settings->maxlength ?? 255" min="$type->settings->minlength ?? null" max="$type->settings->maxlength ?? null"/>
                <div class="input-group-append"><span class="input-group-text">{{trans('content::field.ranglength.unit')}}</span></div>
            </div>                     
        </div>
    </div>  
